<?php

namespace App\Jobs\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

trait Helpers
{
    /**
     * @param $folder
     *
     * @return bool
     */
    protected function createTargetIfNotExists($folder)
    {
        if (!$folder) {
            return false;
        }

        if(File::exists("{$this->path}/{$folder}")) {
            return true;
        }

        return File::makeDirectory("{$this->path}/{$folder}");
    }

    /**
     * @param $filename
     *
     * @return bool|string
     */
    protected function generateSubFolderPath($filename)
    {
        if (strpos($filename, 'IMG') === false) {
            return false;
        }

        $dateFromFilename = explode('_', $filename)[1];

        if (strlen($dateFromFilename) !== 8) {
            return false;
        }

        // Year Month Day, without delimiters: 20180101
        $parsedDate = Carbon::createFromFormat('Ymd', $dateFromFilename);

        if ($parsedDate->year !== (int) substr($dateFromFilename, 0, 4)) {
            return false;
        }

        return sprintf("%d-%'.02d", $parsedDate->year, $parsedDate->month);
    }

    /**
     * Load a collection of files.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function files()
    {
        return collect(File::files($this->path));
    }

    /**
     * @param \Symfony\Component\Finder\SplFileInfo $file
     *
     * @param $target
     *
     * @return bool
     */
    protected function moveFile(SplFileInfo $file, $target)
    {
        if (!$target) {
            return false;
        }

        return File::move(
            $file->getPathname(),
            "{$this->path}/{$target}/{$file->getFilename()}"
        );
    }
}