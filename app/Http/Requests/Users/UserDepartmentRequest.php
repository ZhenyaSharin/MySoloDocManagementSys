<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequest;

class UserDepartmentRequest extends BaseRequest
{

    private $userDepartmentDto;

    public function __construct(UserDepartmentDto $userDepartmentDto)
    {
        $this->userDepartmentDto = $userDepartmentDto;
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
            'departmentId' => [
                'integer',
            ],
        ];
    }

    public function messages()
    {
        return [
            '*.integer' => 'Wrong type',
        ];
    }

    public function data()
    {
        $this->userDepartmentDto->id = $this->input('id');
        $this->userDepartmentDto->userId = $this->input('userId');
        $this->userDepartmentDto->departmentId = $this->input('departmentId');
        $this->userDepartmentDto->createdAt = $this->input('createdAt');
        $this->userDepartmentDto->updatedId = $this->input('updatedId');
        $this->userDepartmentDto->removed = $this->input('removed');

        return $this->userDepartmentDto;
    }
}
