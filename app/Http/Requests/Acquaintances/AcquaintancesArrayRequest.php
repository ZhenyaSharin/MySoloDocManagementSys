<?php

namespace App\Http\Requests\Acquaintances;

use App\Dto\AcquaintanceDto\AcquaintanceDto;
use App\Http\Requests\BaseRequest;

class AcquaintancesArrayRequest extends BaseRequest
{
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
            'userId' => [
                'integer',
            ],
            'initiatorId' => [
                'integer',
            ],
        ];
    }

    public function data()
    {
        $acquaintances = [];
        foreach ($this->input() as $item) {
            $this->acquaintanceDto = new AcquaintanceDto;
            $this->acquaintanceDto->id = $item['id'] ?? null;
            $this->acquaintanceDto->documentId = $item['documentId'] ?? null;
            $this->acquaintanceDto->userId = $item['userId'] ?? null;
            $this->acquaintanceDto->initiatorId = $item['initiatorId'] ?? null;
            $this->acquaintanceDto->seenAt = $item['seenAt'] ?? null;
            $this->acquaintanceDto->createdAt = $item['createdAt'] ?? null;
            $this->acquaintanceDto->updatedAt = $item['updatedAt'] ?? null;
            $this->acquaintanceDto->removed = $item['removed'] ?? null;
            $this->acquaintanceDto->notViewed = $item['notViewed'] ?? null;
            $this->acquaintanceDto->delete = $item['delete'] ?? null;
            $this->acquaintanceDto->view = $item['view'] ?? null;
            $acquaintances[] = $this->acquaintanceDto;
        }
        return $acquaintances;
    }
}
