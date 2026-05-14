<?php

namespace App\Http\Requests\Agreements;

use App\Dto\AgreementDto\AgreementDto;
use App\Dto\AgreementDto\AgreementUserDto;
use App\Http\Requests\BaseRequest;

class AgreementRequest extends BaseRequest
{
    private $agreementDto;

    public function __construct(AgreementDto $agreementDto)
    {
        $this->agreementDto = $agreementDto;
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
            // 'userId' => [
            //     'integer', 'required'
            // ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'You have to fill in the NAME field.',
            '*.integer' => 'Wrong data type',
        ];
    }

    public function data()
    {
        $this->agreementDto->id = $this->input('id') ?? null;
        $this->agreementDto->documentId = $this->input('documentId');
        $this->agreementDto->agreedAt = $this->input('agreedAt') ?? null;
        $this->agreementDto->createdAt = $this->input('createdAt') ?? null;
        $this->agreementDto->updatedAt = $this->input('updatedAt') ?? null;
        $this->agreementDto->removed = $this->input('removed') ?? null;
        $this->agreementDto->delete = $this->input('delete') ?? null;
        $this->agreementDto->orderable = $this->input('orderable') ?? null;
        $this->agreementDto->deadline = $this->input('deadline') ?? null;
        $this->agreementDto->status = $this->input('status') ?? null;
        $this->agreementDto->completed = $this->input('completed') ?? null;
        if ($this->input('users') != null) {
            foreach ($this->input('users') as $item) {
                $user = new AgreementUserDto;
                $user->userId = $item['userId'];
                $user->order = $item['order'];
                $this->agreementDto->users[] = $user;
            }
        }

        return $this->agreementDto;
    }
}
