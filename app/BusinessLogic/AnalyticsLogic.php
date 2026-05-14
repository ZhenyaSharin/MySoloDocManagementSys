<?php

namespace App\BusinessLogic;

use App\Exceptions\BusinessLogicException;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\AssignmentsRepositoryInterface;
use App\Models\Repositories\Contracts\DocumentsRepositoryInterface;
use Throwable;

class AnalyticsLogic
{
    private $docs;
    private $assigns;

    public function __construct(DocumentsRepositoryInterface $docs, AssignmentsRepositoryInterface $assigns)
    {
        $this->docs = $docs;
        $this->assigns = $assigns;
    }

    public function getDocumentsList()
    {
        try {
            return $this->docs->list();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getAssignmentsList()
    {
        try {
            return $this->assigns->list();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }
}
