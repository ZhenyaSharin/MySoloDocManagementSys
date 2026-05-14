<?php

namespace App\Http\Requests\Files;

use App\Dto\FileDto\FileDto;
use App\Http\Requests\BaseRequest;

class FileRequest extends BaseRequest
{
    private $fileDto;

    // public function __construct(FileAdditionDto $fileAdditionDto, FileDto $fileDto)
    public function __construct(FileDto $fileDto)
    {
        $this->fileDto = $fileDto;
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
    public function messages()
    {
        return [
            '*.required' => 'Error. Some of value is empty.',
            // '*.integer' => 'Error. Type must be an integer.',
            // '*.image' => 'Error. The file is not an image.',
            '*.mimetypes' => 'Error. Wrong format of the file.',
            '*.max' => 'Error. Max size of the file is 8 MB.',
        ];
    }

    public function rules()
    {
        // Дописать ошибки
        return [
            'id' => [
                'integer',
            ],
            'count' => [
                'integer',
            ],
            'file.*' => [
                // 'mimetypes:image/jpeg,image/png,image/gif, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/pdf, application/zip, application/x-rar-compressed',
                'max:20480',
            ],
            // 'description' => [
            //     'string', 'required'
            // ]
        ];
    }

    public function data()
    {
        $this->fileDto->id = $this->input('id');
        $this->fileDto->file = $this->input('file');
        $this->fileDto->format = $this->input('format');
        $this->fileDto->createdAt = $this->input('createdAt');
        $this->fileDto->updatedAt = $this->input('updatedAt');
        $this->fileDto->removed = $this->input('removed');
        $this->fileDto->type = $this->input('type');
        $this->fileDto->comment = $this->input('comment');
        $this->fileDto->delete = $this->input('delete');
        $this->fileDto->fileLink = $this->input('fileLink');

        return $this->fileDto;
    }
}
