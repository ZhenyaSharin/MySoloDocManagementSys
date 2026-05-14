<?php

namespace App\Http\Requests\Users;

use App\Dto\UserDto\MailsettingsUserDto;
use App\Http\Requests\BaseRequest;

class MailsettingsUserRequest extends BaseRequest
{
    private $mailsettingsUserDto;

    public function __construct(MailsettingsUserDto $mailsettingsUserDto)
    {
        $this->mailsettingsUserDto = $mailsettingsUserDto;
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
            'settingId' => [
                'integer',
            ],
            'status' => [
                'boolean',
            ],
        ];
    }

    public function messages()
    {
        return [
            '*.integer' => 'Wrong type',
            '*.boolean' => 'Wrong type',
        ];
    }

    public function data()
    {
        $this->mailsettingsUserDto->id = $this->input('id') ?? null;
        $this->mailsettingsUserDto->userId = $this->input('userId') ?? null;
        $this->mailsettingsUserDto->settingId = $this->input('settingId') ?? null;
        $this->mailsettingsUserDto->createdAt = $this->input('createdAt') ?? null;
        $this->mailsettingsUserDto->updatedId = $this->input('updatedId') ?? null;
        $this->mailsettingsUserDto->removed = $this->input('removed') ?? null;
        $this->mailsettingsUserDto->status = $this->input('status') ?? null;
        return $this->mailsettingsUserDto;
    }
}
