<?php

namespace App\Models\Repositories\Contracts;

use App\Dto\FileDto\FileAdditionDto;
use App\Dto\FileDto\FileDto;
use App\Dto\FileDto\FileUploadDto;

interface FilesRepositoryInterface
{
    public function addDocFileAddition(FileAdditionDto $data);

    public function addAssignFileAddition(FileAdditionDto $data);

    public function remove(int $id);

    public function removeByDocumentId(int $documentId);

    public function removeByAssignmentId(int $assignmentId);

    public function updateFileComment(FileDto $data);

    public function updateFile(FileUploadDto $data);
}
