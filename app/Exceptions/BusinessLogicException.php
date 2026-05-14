<?php

namespace App\Exceptions;

use Exception;

class BusinessLogicException extends Exception
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
