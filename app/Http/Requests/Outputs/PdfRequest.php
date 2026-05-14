<?php

namespace App\Http\Requests\Outputs;

use App\Dto\OutputDto\PdfDto;
use App\Http\Requests\BaseRequest;

class PdfRequest extends BaseRequest
{
    private $pdfDto;

    public function __construct(PdfDto $pdfDto)
    {
        $this->pdfDto = $pdfDto;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data' => [
                'string',
            ],
            // 'userId' => [
            //     'integer', 'required'
            // ]
        ];
    }

    public function messages()
    {
        return [
            '*.string' => 'Wrong data type',
        ];
    }

    public function data()
    {
        $this->pdfDto->data = $this->input('data');
        return $this->pdfDto;
    }
}
