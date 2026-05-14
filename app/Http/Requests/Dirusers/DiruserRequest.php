<?php

namespace App\Http\Requests\Disrusers;

use App\Dto\DiruserDto\DiruserDto;
use App\Http\Requests\BaseRequest;

class DocumentRequest extends BaseRequest
{

    private $diruserDto;

    public function __construct(DiruserDto $diruserDto)
    {
        $this->diruserDto = $diruserDto;
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
            'surname' => [
                'string', 'max:64',
            ],
            'firstname' => [
                'string', 'max:64',
            ],
            'patronymic' => [
                'string', 'max:64',
            ],
            'departmentId' => [
                'integer',
            ],
        ];
    }

    public function messages()
    {
        return [
            '*.integer' => 'Wrong data type',
            '*.string' => 'Wrong data type',
            '*.max' => 'so many characters in the string',
        ];
    }

    public function data()
    {
        $this->diruserDto->id = $this->input('id');
        $this->diruserDto->surname = $this->input('surname') ?? null;
        $this->diruserDto->firstname = $this->input('firstname') ?? null;
        $this->diruserDto->patronymic = $this->input('patronymic') ?? null;
        $this->diruserDto->createdAt = $this->input('createdAt') ?? null;
        $this->diruserDto->updatedAt = $this->input('updatedAt') ?? null;
        $this->diruserDto->removed = $this->input('removed') ?? null;
        $this->diruserDto->departmentId = $this->input('departmentId') ?? null;

        return $this->diruserDto;
    }
}
