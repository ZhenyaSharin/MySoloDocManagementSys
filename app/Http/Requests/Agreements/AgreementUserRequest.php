<?php

namespace App\Http\Requests\Agreements;

use App\Dto\AgreementDto\AgreementUserDto;
use App\Http\Requests\BaseRequest;

class AgreementUserRequest extends BaseRequest
{
    private $agreementUserDto;

    public function __construct(AgreementUserDto $agreementUserDto)
    {
        $this->agreementUserDto = $agreementUserDto;
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
            //     'integer',
            // ],
            'surname' => [
                'string',
            ],
            'firstname' => [
                'string',
            ],
            'patronymic' => [
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'You have to fill in the NAME field.',
            '*.integer' => 'Wrong data type',
            '*.string' => 'Wrong data type',
        ];
    }

    public function data()
    {
        $this->agreementUserDto->id = $this->input('id') ?? null;
        $this->agreementUserDto->agreementId = $this->input('agreementId');
        $this->agreementUserDto->userId = $this->input('userId');
        $this->agreementUserDto->note = $this->input('note') ?? null;
        $this->agreementUserDto->createdAt = $this->input('createdAt') ?? null;
        $this->agreementUserDto->updatedAt = $this->input('updatedAt') ?? null;
        $this->agreementUserDto->removed = $this->input('removed') ?? null;
        $this->agreementUserDto->refusedAt = $this->input('refusedAt') ?? null;
        $this->agreementUserDto->delete = $this->input('delete') ?? null;
        $this->agreementUserDto->approve = $this->input('approve') ?? null;
        $this->agreementUserDto->viewed = $this->input('viewed') ?? null;
        $this->agreementUserDto->count = $this->input('count') ?? null;
        $this->agreementUserDto->order = $this->input('order') ?? null;
        $this->agreementUserDto->surname = $this->input('surname') ?? null;
        $this->agreementUserDto->firstname = $this->input('firstname') ?? null;
        $this->agreementUserDto->patronymic = $this->input('patronymic') ?? null;
        $this->agreementUserDto->authorId = $this->input('authorId') ?? null;
        $this->agreementUserDto->documentId = $this->input('documentId') ?? null;
        $this->agreementUserDto->docComplete = $this->input('docComplete') ?? null;
        return $this->agreementUserDto;
    }
}
