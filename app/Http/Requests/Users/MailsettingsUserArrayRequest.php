<?php

namespace App\Http\Requests\Users;

use App\Dto\UserDto\MailsettingsUserDto;
use App\Http\Requests\BaseRequest;

class MailsettingsUserArrayRequest extends BaseRequest
{
    // private $mailsettingsUserDto;

    // public function __construct(MailsettingsUserDto $mailsettingsUserDto)
    // {
    //     $this->mailsettingsUserDto = $mailsettingsUserDto;
    // }
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
        $mailSettings = [];
        foreach ($this->input('mailSettings') as $item) {
            $mailSettingDto = new MailsettingsUserDto();
            $mailSettingDto->id = $item['id'] ?? null;
            $mailSettingDto->userId = $item['userId'] ?? null;
            $mailSettingDto->settingId = $item['settingId'] ?? null;
            $mailSettingDto->createdAt = $item['createdAt'] ?? null;
            $mailSettingDto->updatedId = $item['updatedId'] ?? null;
            $mailSettingDto->removed = $item['removed'] ?? null;
            $mailSettingDto->status = $item['status'] ?? null;
            $mailSettings[] = $mailSettingDto;
        }
        return $mailSettings;
    }
}
