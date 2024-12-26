<?php

namespace App\Actions;

use Exception;
use Carbon\Carbon;

class GetDateFromExifData
{
    /**
     * Get a carbonobject from exif data.
     */
    public function handle(string $fullpath): bool|Carbon
    {
        return $this->toCarbonObject($fullpath);
    }

    protected function toCarbonObject(string $fullpath): bool|Carbon
    {
        try {
            // Open a stream
            $stream = fopen($fullpath, 'rb');

            // Read the exif data from the file
            $data = exif_read_data($stream);
        } catch (Exception $e) {
            logger($e->getMessage(), [
                'code' => $e->getCode(),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return false;
        }

        if ($data['FileDateTime']) {
            return Carbon::createFromTimestamp($data['FileDateTime']);
        }

        return false;
    }
}
