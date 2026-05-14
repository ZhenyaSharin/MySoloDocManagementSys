<?php

namespace App\Http\Requests\Relations;

use App\Dto\RelationDto\RelationDto;
use App\Http\Requests\BaseRequest;

class RelationRequest extends BaseRequest
{

    private $relationDto;

    public function __construct(RelationDto $relationDto)
    {
        $this->relationDto = $relationDto;
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'integer',
            ],
            'documentId1' => [
                'integer',
            ],
            'documentId2' => [
                'integer',
            ],
            'assignmentId1' => [
                'integer',
            ],
            'assignmentId2' => [
                'integer',
            ],
            'userId' => [
                'integer',
            ],
        ];
    }

    public function data()
    {
        $this->relationDto->id = $this->input('id') ?? null;
        $this->relationDto->documentId1 = $this->input('documentId1');
        $this->relationDto->documentId2 = $this->input('documentId2');
        $this->relationDto->assignmentId1 = $this->input('assignmentId1');
        $this->relationDto->assignmentId2 = $this->input('assignmentId2');
        $this->relationDto->agreedAt = $this->input('agreedAt') ?? null;
        $this->relationDto->createdAt = $this->input('createdAt') ?? null;
        $this->relationDto->updatedAt = $this->input('updatedAt') ?? null;
        $this->relationDto->userId = $this->input('userId') ?? null;
        $this->relationDto->remove = $this->input('remove') ?? null;

        return $this->relationDto;
    }
}
