<?php

namespace App\Http\Requests\Relations;

use App\Http\Requests\BaseRequest;
use App\Dto\RelationDto\RelationDto;
use App\Dto\RelationDto\RelationArrayDto;

class RelationArrayRequest extends BaseRequest
{
    private $relationArrayDto;

    public function __construct(RelationArrayDto $relationArrayDto)
    {
        $this->relationArrayDto = $relationArrayDto;
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
            // 'relations' => [
            //     'array',
            // ],
        ];
    }

    public function data()
    {
        $this->relationArrayDto->remove = $this->input('remove') ?? null;
        if ($this->input('relations') != null) {
            foreach ($this->input('relations') as $item) {
                $relationDto = new RelationDto;
                $relationDto->id = $item['id'] ?? null;
                $relationDto->documentId1 = $item['documentId1'];
                $relationDto->documentId2 = $item['documentId2'];
                $relationDto->assignmentId1 = $item['assignmentId1'];
                $relationDto->assignmentId2 = $item['assignmentId2'];
                $relationDto->agreedAt = $item['agreedAt'] ?? null;
                $relationDto->createdAt = $item['createdAt'] ?? null;
                $relationDto->updatedAt = $item['updatedAt'] ?? null;
                $relationDto->userId = $item['userId'] ?? null;
                $this->relationArrayDto->relations[] = $relationDto;
            }
        }
        return $this->relationArrayDto;
    }
}
