<?php

namespace App\Exceptions;

use Exception;

class UnknownStrategyException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param $message
     */
    public function __construct()
    {
        parent::__construct('The strategy cannot be found.');
    }
}
