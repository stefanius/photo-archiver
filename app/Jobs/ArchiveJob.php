<?php

namespace App\Jobs;

use App\Actions\GetDateFromExifData;
use App\Actions\GetDateFromFilename;
use App\Actions\GetDateFromLastModifiedDate;
use App\Exceptions\NonExistingPathException;
use App\Strategies\Strategy;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class ArchiveJob
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var \App\Strategies\Strategy
     */
    protected $strategy;

    /**
     * Constructor.
     */
    public function __construct(string $path, Strategy $strategy)
    {
        $this->path = $path;

        $this->strategy = $strategy;

        if (! File::exists($this->path)) {
            throw new NonExistingPathException($this->path);
        }
    }

    /**
     * Handle job.
     *
     * @return void
     */
    public function handle()
    {
        $this->files()->each(function (SplFileInfo $file) {
            $target = $this->generateSubFolderPath($file->getFilename());

            $this->createTargetIfNotExists($target);
            $this->moveFile($file, $target);
        });
    }


    /**
     * @param string $folder
     *
     * @return boolean
     */
    public function createTargetIfNotExists(string $folder): bool
    {
        if (! $folder) {
            return false;
        }

        if (File::exists("{$this->path}/{$folder}")) {
            return true;
        }

        return File::makeDirectory(path: "{$this->path}/{$folder}", recursive: true);
    }

    /**
     * @param string $filename
     *
     * @return boolean|string
     */
    public function generateSubFolderPath(string $filename): bool|string
    {
        if ($fromFilename = app(GetDateFromFilename::class)->handle($filename)) {
            return $this->strategy->pathFromDate($fromFilename);
        }

        if ($fromExif = app(GetDateFromExifData::class)->handle("{$this->path}/{$filename}")) {
            return $this->strategy->pathFromDate($fromExif);
        }

        if ($fromModifiedDate = app(GetDateFromLastModifiedDate::class)->handle("{$this->path}/{$filename}")) {
            return $this->strategy->pathFromDate($fromModifiedDate);
        }

        return false;
    }

    /**
     * Load a collection of files.
     *
     * @return \Illuminate\Support\Collection
     */
    public function files(): Collection
    {
        return collect(File::files($this->path));
    }

    /**
     * Undocumented function
     *
     * @param \Symfony\Component\Finder\SplFileInfo $file
     * @param string $target
     *
     * @return boolean
     */
    public function moveFile(SplFileInfo $file, string $target): bool
    {
        if (! $target) {
            return false;
        }

        return File::move(
            $file->getPathname(),
            "{$this->path}/{$target}/{$file->getFilename()}"
        );
    }
}
