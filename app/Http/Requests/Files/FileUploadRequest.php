<?php

namespace App\Http\Requests\Files;

use App\Dto\FileDto\FileUploadDto;
use App\Http\Requests\BaseRequest;

class FileUploadRequest extends BaseRequest
{
    private $fileUploadDto;

    public function __construct(FileUploadDto $fileUploadDto)
    {
        $this->fileUploadDto = $fileUploadDto;
    }

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
            'documentId' => [
                'integer',
            ],
            'file.*' => [
                // 'required',
                // 'mimetypes: application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'max:102400',
            ],
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'Error. Some of value is empty.',
            // '*.integer' => 'Error. Type must be an integer.',
            // '*.image' => 'Error. The file is not an image.',
            '*.mimetypes' => 'Error. Wrong format of the file.',
            '*.max' => 'Error. Max size of the file is 100 MB.',
            '*.integer' => 'Wrong data type',
        ];
    }

    public function data()
    {
        $this->fileUploadDto->documentId = $this->input('documentId');
        $this->fileUploadDto->file = $this->file;
        $this->fileUploadDto->fileFormat = $this->input('fileFormat') ?? null;
        $this->fileUploadDto->fileLink = $this->input('fileLink') ?? null;
        return $this->fileUploadDto;
    }
}
