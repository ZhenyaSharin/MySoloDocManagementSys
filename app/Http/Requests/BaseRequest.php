<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    public function createErrorString($data)
    {
        $collection = collect(["error" => "1", "errors" => $data->failed()]);
        return $collection;
    }
}
