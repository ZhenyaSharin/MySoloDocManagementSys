<?php

namespace App\Dto\AssignmentDto;

class AssignmentDeadlineDto
{
    public $id;
    public $assignmentId;
    public $initiatorId;
    public $approvedUserId;
    public $createdAt;
    public $updatedAt;
    public $removed;
    public $deadline;
    public $initiatedAt;
    public $approvedAt;
    public $refusedAt;
    public $comment;
    public $fileId;
    public $file;
    public $approve;
    public $refuse;
    public $authorId;
}
