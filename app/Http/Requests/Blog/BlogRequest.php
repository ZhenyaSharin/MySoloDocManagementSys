<?php

namespace App\Http\Requests\Blog;

use App\Dto\BlogDto\BlogDto;
use App\Http\Requests\BaseRequest;

class BlogRequest extends BaseRequest
{

    private $blogDto;

    public function __construct(BlogDto $blogDto)
    {
        $this->blogDto = $blogDto;
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
            'title' => [
                'string', 'min:1', 'max:64', 'required',
            ],
            'text' => [
                'string', 'max:512', 'nullable',
            ],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'You have to fill in the Title field.',
            '*.integer' => 'Wrong data type',
            '*.string' => 'Wrong data type',
            '*.max' => 'so many characters in the string',
        ];
    }

    public function data()
    {
        $this->blogDto->id = $this->input('id') ?? null;
        $this->blogDto->title = $this->input('title') ?? null;
        $this->blogDto->text = $this->input('text') ?? null;
        $this->blogDto->createdAt = $this->input('createdAt') ?? null;
        $this->blogDto->updatedAt = $this->input('updatedAt') ?? null;
        $this->blogDto->removed = $this->input('removed') ?? null;
        $this->blogDto->delete = $this->input('delete') ?? null;
        return $this->blogDto;
    }
}
