<?php

namespace App\Http\Requests\Assignments;

use App\Dto\AssignmentDto\AssignmentDeadlineDto;
use App\Http\Requests\BaseRequest;

class AssignmentDeadlineRequest extends BaseRequest
{
    private $assignmentDeadlineDto;

    public function __construct(AssignmentDeadlineDto $assignmentDeadlineDto)
    {
        $this->assignmentDeadlineDto = $assignmentDeadlineDto;
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
            'assignmentId' => [
                'integer',
            ],
            'typeId' => [
                'integer',
            ],
            'initiatorId' => [
                'integer',
            ],
            'approvedUserId' => [
                'integer',
            ],
            'deadline' => [
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            // 'text.required' => 'Поле "Название" не заполнено...',
            '*.integer' => 'Wrong data type',
            '*.string' => 'Wrong data type',
            // '*.mimetypes' => 'Error. Wrong format of the file.',
            // '*.max' => 'Error. Max size of the file is 8 MB.',
            // 'description.required' => 'Поле "Текст поручения" не заполнено...',
        ];
    }

    public function data()
    {
        $this->assignmentDeadlineDto->id = $this->input('id');
        $this->assignmentDeadlineDto->assignmentId = $this->input('assignmentId');
        $this->assignmentDeadlineDto->initiatorId = $this->input('initiatorId');
        $this->assignmentDeadlineDto->approvedUserId = $this->input('approvedUserId');
        $this->assignmentDeadlineDto->createdAt = $this->input('createdAt');
        $this->assignmentDeadlineDto->updatedAt = $this->input('updatedAt');
        $this->assignmentDeadlineDto->removed = $this->input('removed');
        $this->assignmentDeadlineDto->deadline = $this->input('deadline');
        $this->assignmentDeadlineDto->initiatedAt = $this->input('initiatedAt');
        $this->assignmentDeadlineDto->approvedAt = $this->input('approvedAt');
        $this->assignmentDeadlineDto->refusedAt = $this->input('refusedAt');
        $this->assignmentDeadlineDto->comment = $this->input('comment');
        $this->assignmentDeadlineDto->fileId = $this->input('fileId');
        $this->assignmentDeadlineDto->file = $this->input('file');
        $this->assignmentDeadlineDto->approve = $this->input('approve');
        $this->assignmentDeadlineDto->refuse = $this->input('refuse');
        $this->assignmentDeadlineDto->authorId = $this->input('authorId');

        return $this->assignmentDeadlineDto;
    }
}
