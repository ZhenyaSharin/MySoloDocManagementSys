<?php

namespace App\Exceptions;

use Exception;

class DatabaseException extends Exception
{
    private $error;

    public function __construct($response = null)
    {
        $this->error = $response;
    }

    public function errors()
    {
        return $this->error;
    }
}
