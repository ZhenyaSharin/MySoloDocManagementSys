<?php

namespace App\Http\Requests\Files;

use App\Dto\FileDto\FileAdditionDto;
use App\Dto\FileDto\FileDto;
use App\Http\Requests\BaseRequest;

class FileAdditionRequest extends BaseRequest
{
    private $fileAdditionDto;
    private $fileDto;

    // public function __construct(FileAdditionDto $fileAdditionDto, FileDto $fileDto)
    public function __construct(FileAdditionDto $fileAdditionDto)
    {
        $this->fileAdditionDto = $fileAdditionDto;
        // $this->fileDto = $fileDto;
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
            '*.max' => 'Error. Max size of the file is 100 MB.',
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
                'max:102400',
            ],
            // 'description' => [
            //     'string', 'required'
            // ]
        ];
    }

    public function data()
    {
        $this->fileAdditionDto->id = $this->input('id');
        if (!empty($this->file('files'))) {
            $files = [];
            $fileDto = new FileDto;
            $files = $this->file('files');
            for ($i = 0; $i < count($files); $i++) {
                $fileDto = new FileDto;
                $fileDto->id = $files[$i]->id ?? null;
                $fileDto->file = $files[$i];
                $fileDto->format = $files[$i]->format ?? null;
                $fileDto->createdAt = $files[$i]->createdAt ?? null;
                $fileDto->updatedAt = $files[$i]->updatedAt ?? null;
                $fileDto->removed = $files[$i]->removed ?? null;
                $fileDto->type = $files[$i]->type ?? null;
                $fileDto->comment = $this->comment[$i] ?? null;
                $fileDto->delete = $files[$i]->delete ?? null;
                $this->fileAdditionDto->files[] = $fileDto;
            }
        }
        $this->fileAdditionDto->documentId = $this->input('documentId');
        $this->fileAdditionDto->assignmentId = $this->input('assignmentId');
        $this->fileAdditionDto->feedbackId = $this->input('feedbackId');
        $this->fileAdditionDto->blogId = $this->input('blogId');
        $this->fileAdditionDto->agreementAndUserId = $this->input('agreementAndUserId');
        $this->fileAdditionDto->createdAt = $this->input('createdAt');
        $this->fileAdditionDto->updatedAt = $this->input('updatedAt');
        $this->fileAdditionDto->removed = $this->input('removed');
        $this->fileAdditionDto->delete = $this->input('delete');

        return $this->fileAdditionDto;
    }
}
