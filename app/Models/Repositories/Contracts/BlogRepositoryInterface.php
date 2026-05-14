<?php

namespace App\Models\Repositories\Contracts;

use App\Dto\BlogDto\BlogDto;

interface BlogRepositoryInterface
{
    function list();

    public function add(BlogDto $data);

    public function update(BlogDto $data);

    public function remove(int $id);
}
