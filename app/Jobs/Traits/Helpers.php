<?php

namespace App\Jobs\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Finder\SplFileInfo;

trait Helpers
{
    /**
     * @return bool
     */
    protected function createTargetIfNotExists($folder)
    {
        if (! $folder) {
            return false;
        }

        if (File::exists("{$this->path}/{$folder}")) {
            return true;
        }

        return File::makeDirectory("{$this->path}/{$folder}");
    }

    protected function explode($filename)
    {
        if (Str::contains($filename, '-')) {
            return explode('-', $filename);
        }

        if (Str::contains($filename, '_')) {
            return explode('_', $filename);
        }

        throw new \Exception('Wrong file name: '. $filename);
    }

    protected function generateSubFolderPath($filename)
    {
        $fromFilename = $this->generateSubFolderPathFromFilename($filename);

        try {
            if (!$fromFilename) {
                $fullPath = "{$this->path}/{$filename}";
                $stream = fopen($fullPath, 'rb');

                $data = exif_read_data($stream);

                if ($data['FileDateTime']) {
                    $fileDateTime = Carbon::createFromTimestamp($data['FileDateTime']);

                    return sprintf("%d-%'.02d", $fileDateTime->year, $fileDateTime->month);
                }
            }
        } catch (\Exception $e) {
            !$fromFilename = false;
        }


        if (!$fromFilename) {
            $fullPath = "{$this->path}/{$filename}";
            $lastModified = File::lastModified($fullPath);
            $lastModifiedDate = Carbon::createFromTimestamp($lastModified);

            return sprintf("%d-%'.02d", $lastModifiedDate->year, $lastModifiedDate->month);
        }

        return $fromFilename;
    }

    /**
     * @return bool|string
     */
    protected function generateSubFolderPathFromFilename($filename)
    {
        if (Str::contains($filename, ['IMG', 'VID'], true) === false) {
            return false;
        }

        $dateFromFilename = $this->explode($filename)[1];

        if (!$this->canParseDateFromString($dateFromFilename)) {
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
     * @return bool
     */
    protected function moveFile(SplFileInfo $file, $target)
    {
        if (! $target) {
            return false;
        }

        return File::move(
            $file->getPathname(),
            "{$this->path}/{$target}/{$file->getFilename()}"
        );
    }

    /**
     * @param string $dateFromFilename
     *
     * @return boolean
     */
    protected function canParseDateFromString(string $dateFromFilename): bool
    {
        // If the length is not exactly 8 characters (yyyymmdd format) return false
        if (strlen(trim($dateFromFilename)) !== 8) {
            return false;
        }

        // Numeric check to exclude non-nummeric matches
        return is_numeric($dateFromFilename);
    }
}
