<?php

namespace App\Models\Repositories\Contracts;

use App\Dto\AcquaintanceDto\AcquaintanceDto;

interface AcquaintanceRepositoryInterface
{
    function list();

    public function listByUserId(int $userId);

    public function listByUserIdNonViewed(int $userId);

    public function listByInitiatorId(int $userId);

    public function listByDocumentId(int $documentId);

    public function add(array $data);

    public function makeSeen(AcquaintanceDto $data);

    public function remove(int $id);
}
