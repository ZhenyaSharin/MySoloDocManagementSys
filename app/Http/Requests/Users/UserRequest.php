<?php

namespace App\Http\Requests\Users;

use App\Dto\UserDto\UserDto;
use App\Http\Requests\BaseRequest;

class UserRequest extends BaseRequest
{

    private $userDto;

    public function __construct(UserDto $userDto)
    {
        $this->userDto = $userDto;
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
            'updated_at' => [
                'nullable',
            ],
            'removed' => [
                'nullable',
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
        $this->userDto->id = $this->input('id');
        $this->userDto->login = $this->input('login');
        $this->userDto->surname = $this->input('surname');
        $this->userDto->firstname = $this->input('firstname');
        $this->userDto->patronymic = $this->input('patronymic');
        $this->userDto->department = json_decode($this->input('department'), true);
        $this->userDto->email = $this->input('email');
        $this->userDto->createdAt = $this->input('created_at');
        $this->userDto->updatedAt = $this->input('updated_at');
        $this->userDto->roleId = $this->input('roleid');
        $this->userDto->removed = $this->input('removed');
        $this->userDto->delete = $this->input('delete') ?? null;
        $this->userDto->count = $this->input('count') ?? null;
        $this->userDto->info = $this->input('info') ?? null;
        return $this->userDto;
    }
}
