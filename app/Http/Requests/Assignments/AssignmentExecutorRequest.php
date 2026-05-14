<?php

namespace App\Http\Requests\Assignments;

use App\Dto\AssignmentDto\AssignmentExecutorDto;
use App\Http\Requests\BaseRequest;

class AssignmentExecutorRequest extends BaseRequest
{
    private $assignmentExecutorDto;

    public function __construct(AssignmentExecutorDto $assignmentExecutorDto)
    {
        $this->assignmentExecutorDto = $assignmentExecutorDto;
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
            'assignmentId' => [
                'integer',
            ],
            'executorId' => [
                'integer',
            ],
            'order' => [
                'integer',
            ],
        ];
    }

    public function data()
    {
        $this->assignmentExecutorDto->id = $this->input('id');
        $this->assignmentExecutorDto->assignmentId = $this->input('assignmentId');
        $this->assignmentExecutorDto->executorId = $this->input('executorId');
        $this->assignmentExecutorDto->order = $this->input('order');
        $this->assignmentExecutorDto->createdAt = $this->input('createdAt');
        $this->assignmentExecutorDto->updatedAt = $this->input('updatedAt');
        $this->assignmentExecutorDto->removed = $this->input('removed');
        $this->assignmentExecutorDto->completedAt = $this->input('completedAt');
        $this->assignmentExecutorDto->viewedAt = $this->input('viewedAt');
        $this->assignmentExecutorDto->refusedAt = $this->input('refusedAt');
        $this->assignmentExecutorDto->count = $this->input('count');
        $this->assignmentExecutorDto->refuse = $this->input('refuse');
        $this->assignmentExecutorDto->complete = $this->input('complete');
        $this->assignmentExecutorDto->viewed = $this->input('viewed');

        return $this->assignmentExecutorDto;
    }
}
