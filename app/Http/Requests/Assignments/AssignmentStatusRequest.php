<?php

namespace App\Http\Requests\Assignments;

use App\Dto\AssignmentDto\AssignmentStatusDto;
use App\Http\Requests\BaseRequest;

class AssignmentStatusRequest extends BaseRequest
{
    private $assignmentStatusDto;

    public function __construct(AssignmentStatusDto $assignmentStatusDto)
    {
        $this->assignmentStatusDto = $assignmentStatusDto;
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
            'statusId' => [
                'integer',
            ],
            'note' => [
                'string', 'max:512', 'nullable',
            ],
            'alias' => [
                'string', 'max:64',
            ],
            'executorId' => [
                'integer',
            ],
        ];
    }

    public function data()
    {
        $this->assignmentStatusDto->id = $this->input('id');
        $this->assignmentStatusDto->assignmentId = $this->input('assignmentId');
        $this->assignmentStatusDto->statusId = $this->input('statusId');
        $this->assignmentStatusDto->createdAt = $this->input('createdAt');
        $this->assignmentStatusDto->updatedAt = $this->input('updatedAt');
        $this->assignmentStatusDto->removed = $this->input('removed');
        $this->assignmentStatusDto->note = $this->input('note');
        $this->assignmentStatusDto->alias = $this->input('alias');
        $this->assignmentStatusDto->deadline = $this->input('deadline');
        $this->assignmentStatusDto->executorId = $this->input('executorId');
        $this->assignmentStatusDto->authorId = $this->input('authorId');

        return $this->assignmentStatusDto;
    }
}
