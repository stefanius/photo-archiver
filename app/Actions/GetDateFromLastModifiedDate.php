<?php

namespace App\Actions;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\File;

class GetDateFromLastModifiedDate
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
            $lastModified = File::lastModified($fullpath);
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

        return Carbon::createFromTimestamp($lastModified);
    }
}
