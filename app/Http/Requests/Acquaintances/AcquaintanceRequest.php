<?php

namespace App\Http\Requests\Acquaintances;

use App\Dto\AcquaintanceDto\AcquaintanceDto;
use App\Http\Requests\BaseRequest;

class AcquaintanceRequest extends BaseRequest
{
    private $acquaintanceDto;

    public function __construct(AcquaintanceDto $acquaintanceDto)
    {
        $this->acquaintanceDto = $acquaintanceDto;
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
        $this->acquaintanceDto->id = $this->input('id');
        $this->acquaintanceDto->documentId = $this->input('documentId');
        $this->acquaintanceDto->userId = $this->input('userId');
        $this->acquaintanceDto->initiatorId = $this->input('initiatorId');
        $this->acquaintanceDto->seenAt = $this->input('seenAt');
        $this->acquaintanceDto->createdAt = $this->input('createdAt');
        $this->acquaintanceDto->updatedAt = $this->input('updatedAt');
        $this->acquaintanceDto->removed = $this->input('removed');
        $this->acquaintanceDto->notViewed = $this->input('notViewed');
        $this->acquaintanceDto->delete = $this->input('delete');
        $this->acquaintanceDto->view = $this->input('view');

        return $this->acquaintanceDto;
    }

}
