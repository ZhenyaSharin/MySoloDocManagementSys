<?php

namespace App\Http\Requests\Search;

use App\Dto\SearchDto\SearchDto;
use App\Http\Requests\BaseRequest;

class SearchRequest extends BaseRequest
{
    private $searchDto;

    public function __construct(SearchDto $searchDto)
    {
        $this->searchDto = $searchDto;
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
            //
        ];
    }

    public function data()
    {
        $this->searchDto->words = $this->input('words');
        $this->searchDto->users = json_decode($this->input('users'), true);
        $this->searchDto->docTypes = json_decode($this->input('docTypes'), true) ?? null;
        $this->searchDto->assignTypes = json_decode($this->input('assignTypes') ?? null, true);
        $this->searchDto->docStatuses = json_decode($this->input('docStatuses'), true) ?? null;
        $this->searchDto->assignStatuses = json_decode($this->input('assignStatuses'), true) ?? null;
        $this->searchDto->period = json_decode($this->input('period'), true) ?? null;
        $this->searchDto->docDate = json_decode($this->input('docDate'), true) ?? null;
        $this->searchDto->docAuthor = json_decode($this->input('docAuthor') ?? null, true);
        $this->searchDto->assignAuthor = json_decode($this->input('assignAuthor'), true) ?? null;
        $this->searchDto->assignExecutor = json_decode($this->input('assignExecutor'), true) ?? null;
        $this->searchDto->additionalUsers = $this->input('additionalUsers');
        $this->searchDto->orderNum = $this->input('orderNum');

        return $this->searchDto;
    }
}
