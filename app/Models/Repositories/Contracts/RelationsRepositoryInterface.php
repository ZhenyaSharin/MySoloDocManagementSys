<?php

namespace App\Models\Repositories\Contracts;

use App\Dto\DocumentDto\DocumentDto;
use App\Dto\AssignmentDto\AssignmentDto;
use App\Dto\RelationDto\RelationDto;
use App\Dto\RelationDto\RelationArrayDto;


interface RelationsRepositoryInterface
{
	public function list(string $ascDesc);

    public function listByDocId($documentId);

    public function listByAssignId($documentId);

    public function add(RelationArrayDto $data);

    public function remove(int $id);

    public function getById($id);

    public function update(RelationDto $data);
}
