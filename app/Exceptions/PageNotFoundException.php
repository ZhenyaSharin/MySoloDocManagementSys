<?php

namespace App\Exceptions;

use Exception;

class PageNotFoundException extends Exception
{
    private $error;

    public function __construct($response = null)
    {
        $this->error = $response;
    }

    public function errors()
    {
        return abort(404);
    }
}
