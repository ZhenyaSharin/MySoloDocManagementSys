<?php

namespace App\Http\Requests\Assignments;

use App\Dto\AssignmentDto\AssignmentControlDto;
use App\Http\Requests\BaseRequest;

class AssignmentControlRequest extends BaseRequest
{
    private $assignmentControlDto;

    public function __construct(AssignmentControlDto $assignmentControlDto)
    {
        $this->assignmentControlDto = $assignmentControlDto;
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
                'integer',
            ],
            'userId' => [
                'integer',
            ],
            'assignmentId' => [
                'integer',
            ],
            'initiatorId' => [
                'integer',
            ],
            'documentId' => [
                'integer',
            ],
        ];
    }

    public function messages()
    {
        return [
            '*.integer' => 'Ошибка типа данных',
        ];
    }

    public function data()
    {
        $this->assignmentControlDto->id = $this->input('id');
        $this->assignmentControlDto->userId = $this->input('userId');
        $this->assignmentControlDto->assignmentId = $this->input('assignmentId');
        $this->assignmentControlDto->createdAt = $this->input('createdAt');
        $this->assignmentControlDto->updatedAt = $this->input('updatedAt');
        $this->assignmentControlDto->removed = $this->input('removed');
        $this->assignmentControlDto->viewedAt = $this->input('viewedAt');
        $this->assignmentControlDto->initiatorId = $this->input('initiatorId');
        $this->assignmentControlDto->documentId = $this->input('documentId');
        $this->assignmentControlDto->viewed = $this->input('viewed');

        return $this->assignmentControlDto;
    }
}
