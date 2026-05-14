<?php

namespace App\Http\Requests\Assignments;

use App\Dto\AssignmentDto\AssignmentDto;
use App\Dto\FileDto\FileDto;
use App\Http\Requests\BaseRequest;

class AssignmentRequest extends BaseRequest
{
    private $assignmentDto;

    public function __construct(AssignmentDto $assignmentDto)
    {
        $this->assignmentDto = $assignmentDto;
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
            'id' => [
                'integer', 'nullable',
            ],
            'documentId' => [
                'integer', 'nullable',
            ],
            'typeId' => [
                'integer',
            ],
            'authorId' => [
                'integer',
            ],
            'text' => [
                'string', 'max:512',
            ],
            'description' => [
                'string', 'max:2048',
            ],
            'baseId' => [
                'integer', 'nullable',
            ],
        ];
    }

    public function messages()
    {
        return [
            'text.required' => 'Поле "Название" не заполнено...',
            '*.integer' => 'Wrong data type',
            '*.mimetypes' => 'Error. Wrong format of the file.',
            '*.max' => 'Error. Max size of the file is 8 MB.',
            'description.required' => 'Поле "Текст поручения" не заполнено...',
        ];
    }

    public function data()
    {
        $this->assignmentDto->id = $this->input('id');
        $this->assignmentDto->documentId = $this->input('documentId') ?? null;
        $this->assignmentDto->typeId = $this->input('typeId') ?? null;
        $this->assignmentDto->authorId = $this->input('authorId') ?? null;
        $this->assignmentDto->createdAt = $this->input('createdAt') ?? null;
        $this->assignmentDto->updatedAt = $this->input('updatedAt') ?? null;
        $this->assignmentDto->completedAt = $this->input('completedAt') ?? null;
        $this->assignmentDto->removed = $this->input('removed') ?? null;
        $this->assignmentDto->text = $this->input('text') ?? null;
        $this->assignmentDto->description = $this->input('description');
        $this->assignmentDto->executors = json_decode($this->input('executors'), true);
        $this->assignmentDto->baseId = $this->input('baseId') ?? null;
        $this->assignmentDto->delete = $this->input('delete') ?? null;
        $this->assignmentDto->info = $this->input('info') ?? null;
        $this->assignmentDto->deadline = $this->input('deadline') ?? null;
        $this->assignmentDto->count = $this->input('count') ?? null;
        $this->assignmentDto->viewed = $this->input('viewed') ?? null;
        $this->assignmentDto->complete = $this->input('complete') ?? null;
        $this->assignmentDto->refuse = $this->input('refuse') ?? null;
        $this->assignmentDto->executorId = $this->input('executorId') ?? null;
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
                $this->assignmentDto->files[] = $fileDto;
            }
        }
        return $this->assignmentDto;
    }
}
