<?php

namespace App\Exceptions;

use Exception;

class NonExistingPathException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  $message
     */
    public function __construct($path)
    {
        parent::__construct("The path '{$path}' does not exists.");
    }
}
