<?php

namespace App\Jobs;

use App\Actions\GetDateFromExifData;
use App\Actions\GetDateFromFilename;
use App\Actions\GetDateFromLastModifiedDate;
use App\Exceptions\NonExistingPathException;
use App\Strategies\Strategy;
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
     * Undocumented function
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
     * Handle job
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
     * @return bool
     */
    public function createTargetIfNotExists($folder)
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
     * @return string|bool
     */
    public function generateSubFolderPath(string $filename)
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
    public function files()
    {
        return collect(File::files($this->path));
    }

    /**
     * @return bool
     */
    public function moveFile(SplFileInfo $file, $target)
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
