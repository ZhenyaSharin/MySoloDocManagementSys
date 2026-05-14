<?php

namespace App\Http\Requests\Users;

use App\Dto\UserDto\UserPasswordDto;
use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;

class UserPasswordRequest extends BaseRequest
{

    private $userPasswordDto;

    public function __construct(UserPasswordDto $userPasswordDto)
    {
        $this->userPasswordDto = $userPasswordDto;
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
            'login' => [
                // 'required',
                'max:64',
            ],
            'surname' => [
                'max:64', 'nullable',
            ],
            'firstname' => [
                'max:64', 'nullable',
            ],
            'patronymic' => [
                'max:64', 'nullable',
            ],
            // 'department' => [
            //     'integer', 'nullable',
            // ],
            'email' => [
                'max:255',
            ],
            'adminId' => [
                'integer',
            ],
            'roleId' => [
                'integer',
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'You have to fill in the NAME field.',
            '*.integer' => 'Wrong type',
        ];
    }

    public function data()
    {
        $this->userPasswordDto->id = $this->input('id') ?? null;
        $this->userPasswordDto->login = $this->input('login');
        $this->userPasswordDto->surname = $this->input('surname');
        $this->userPasswordDto->firstname = $this->input('firstname');
        $this->userPasswordDto->patronymic = $this->input('patronymic');
        $this->userPasswordDto->password = Hash::make($this->input('password')) ?? null;
        $this->userPasswordDto->department = json_decode($this->input('department'), true);
        $this->userPasswordDto->email = $this->input('email');
        $this->userPasswordDto->roleId = $this->input('roleId');
        $this->userPasswordDto->adminId = $this->input('adminId');
        return $this->userPasswordDto;
    }
}
