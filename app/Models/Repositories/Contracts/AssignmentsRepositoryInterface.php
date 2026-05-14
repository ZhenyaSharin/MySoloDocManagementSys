<?php

namespace App\Models\Repositories\Contracts;

use App\Dto\AssignmentDto\AssignmentControlDto;
use App\Dto\AssignmentDto\AssignmentDeadlineDto;
use App\Dto\AssignmentDto\AssignmentDto;
use App\Dto\AssignmentDto\AssignmentStatusDto;

interface AssignmentsRepositoryInterface
{
    function list();

    public function listWithInfo();

    public function add(AssignmentDto $data);

    public function addWithAddition(AssignmentDto $data);

    public function listAssignmentTypes();

    public function listByAuthorId(int $authorId);

    public function listByAuthorIdWithCount(int $authorId, int $count);

    public function listByExecutorId(int $userId);

    public function listByExecutorIdWithCount(int $userId, int $count);

    public function makeViewedAssignment(AssignmentDto $data);

    public function getById(int $id);

    public function getByIdWithInfo(int $id);

    public function listNonViewedByExecutorId(int $executorId);

    public function updateStatusByAlias(AssignmentStatusDto $data);

    public function updateStatus(AssignmentStatusDto $data);

    public function listLog(int $id);

    public function updateStatusWithNewDeadline(AssignmentStatusDto $data);

    public function addControl(AssignmentControlDto $data);

    public function viewControl(int $id);

    public function controlsByUserId(int $userId);

    public function approveDeadline(int $id, int $userId);

    public function refuseDeadline(int $id, int $userId);

    public function addDeadline(AssignmentDeadlineDto $data);

    public function listAssignStatuses();

    public function updateInfo(AssignmentDto $data);
}
