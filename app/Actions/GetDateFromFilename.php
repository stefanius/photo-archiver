<?php

namespace App\Actions;

use Carbon\Carbon;
use Illuminate\Support\Str;

class GetDateFromFilename
{
    /**
     * Get a carbonobject from a filename.
     */
    public function handle(string $filename): bool|Carbon
    {
        return $this->isFilenameValid($filename) ? $this->extractDate($filename) : false;
    }

    /**
     * Check if a filename is valid.
     *
     * @param string $filename
     *
     * @return bool
     */
    protected function isFilenameValid(string $filename): bool
    {
        return Str::contains($filename, ['IMG', 'VID'], true) && Str::contains($filename, ['-', '_'], true);
    }

    /**
     * Explode the filename into an array.
     *
     * @param string $filename
     *
     * @return array
     */
    public function explode(string $filename): array
    {
        if (Str::contains($filename, '-')) {
            return explode('-', $filename);
        }

        return explode('_', $filename);
    }

    /**
     * Extract date from filename.
     *
     * @param string $filename
     *
     * @return bool|\Carbon\Carbon
     */
    protected function extractDate(string $filename): bool|Carbon
    {
        // Explode the filename into pieces
        $pieces = $this->explode($filename);

        // The date should be on element [1]
        $dateElement = $pieces[1];

        // When Year Month Day, without delimiters: 20180101
        return $this->isDatestringValid($dateElement) ? $this->toCarbonObject($dateElement) : false;
    }

    /**
     * @param string $dateElement
     *
     * @return bool|\Carbon\Carbon
     */
    protected function toCarbonObject(string $dateElement): bool|Carbon
    {
        $carbon = Carbon::createFromFormat('Ymd', $dateElement);

        // Return false when there is no valid carbon object.
        if (is_null($carbon)) {
            return false;
        }

        // Return false when the year is not parsed correctly.
        if ($carbon->year !== (int) substr($dateElement, 0, 4)) {
            return false;
        }

        // And finaly, return the carbon object
        return $carbon;
    }

    /**
     * Check the datestring it matches the yyyymmdd date format.
     *
     * @param string $datestring
     *
     * @return bool
     */
    protected function isDatestringValid(string $datestring): bool
    {
        // If the length is not exactly 8 characters (yyyymmdd format) return false
        if (strlen(trim($datestring)) !== 8) {
            return false;
        }

        // Numeric check to exclude non-nummeric matches
        return is_numeric($datestring);
    }
}
