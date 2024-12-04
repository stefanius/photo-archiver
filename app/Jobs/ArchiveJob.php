<?php

namespace App\Jobs;

use App\Exceptions\NonExistingPathException;
use App\Jobs\Traits\Helpers;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class ArchiveJob
{
    use Helpers;

    /**
     * @var string
     */
    protected $path;

    /**
     * ArchiveJob constructor.
     *
     *
     * @throws \App\Exceptions\NonExistingPathException
     */
    public function __construct($path)
    {
        $this->path = $path;

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
}
