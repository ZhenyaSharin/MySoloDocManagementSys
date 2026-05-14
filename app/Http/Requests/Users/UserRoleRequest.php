<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequest;
use App\Dto\UserDto\UserRoleDto;

class UserRoleRequest extends BaseRequest
{
    private $userRoleDto;

    public function __construct(UserRoleDto $userRoleDto)
    {
        $this->userRoleDto = $userRoleDto;
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
        public function rules()
    {
        return [
            'id' => [
                'integer',
            ],
            'userId' => [
                'integer',
            ],
            'roleId' => [
                'integer',
            ],
            'adminId' => [
                'integer',
            ],
        ];
    }

    public function messages()
    {
        return [
            // 'name.required' => 'You have to fill in the NAME field.',
            '*.integer' => 'Wrong type',
        ];
    }

    public function data()
    {
        $this->userRoleDto->id = $this->input('id');
        $this->userRoleDto->userId = $this->input('userId');
        $this->userRoleDto->roleId = $this->input('roleId');
        $this->userRoleDto->adminId = $this->input('adminId');
        $this->userRoleDto->last = $this->input('last') ?? null;
        return $this->userRoleDto;
    }
}
