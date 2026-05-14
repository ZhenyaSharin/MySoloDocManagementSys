<?php

namespace App\BusinessLogic;

use App\Dto\SearchDto\SearchDto;
use App\Models\Repositories\Contracts\SearchRepositoryInterface;

class SearchLogic
{
    private $search;

    public function __construct(SearchRepositoryInterface $search)
    {
        $this->search = $search;
    }

    public function makeSearch(SearchDto $data)
    {
        try {
            if ($data->additionalUsers === 1) {
                return $this->search->searchAdditionalUsers($data);
            } else {
                return $this->search->search($data);
            };
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }
}
