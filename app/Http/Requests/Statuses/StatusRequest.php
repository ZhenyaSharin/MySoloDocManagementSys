<?php

namespace App\Http\Requests\Statuses;

use App\Dto\StatusDto\StatusDto;
use App\Http\Requests\BaseRequest;

class StatusRequest extends BaseRequest
{
    private $statusDto;

    public function __construct(StatusDto $statusDto)
    {
        $this->statusDto = $statusDto;
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
            'group' => [
                'integer',
            ],
            'title' => [
                'string', 'max:64',
            ],
            'alias' => [
                'string', 'max:64',
            ],
        ];
    }

    public function messages()
    {
        return [
            '*.integer' => 'Wrong data type',
            '*.max' => 'Error. Max size of the file is 8 MB.',
            '*.string' => 'Wrong data type',
        ];
    }

    public function data()
    {
        $this->statusDto->id = $this->input('id');
        $this->statusDto->title = $this->input('title');
        $this->statusDto->createdAt = $this->input('createdAt');
        $this->statusDto->updatedAt = $this->input('updatedAt');
        $this->statusDto->removed = $this->input('removed');
        $this->statusDto->alias = $this->input('alias');
        $this->statusDto->group = $this->input('group');

        return $this->statusDto;
    }
}
