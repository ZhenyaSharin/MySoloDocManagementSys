<?php

namespace App\Models\Repositories\Contracts;

use App\Dto\SearchDto\SearchDto;

interface SearchRepositoryInterface
{
    public function search(SearchDto $data);

    public function searchAdditionalUsers(SearchDto $data);
}
