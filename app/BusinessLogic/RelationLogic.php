<?php

namespace App\BusinessLogic;

use App\Dto\RelationDto\RelationDto;
use App\Dto\RelationDto\RelationArrayDto;
use App\Exceptions\BusinessLogicException;
use App\Exceptions\DatabaseException;
use App\Mail\SystemMail;
use App\Models\Repositories\Contracts\RelationsRepositoryInterface;
use Throwable;

class RelationLogic
{
    private $relations;

    public function __construct(RelationsRepositoryInterface $relations)
    {
        $this->relations = $relations;
    }

    public function addNewRelation(RelationArrayDto $data)
    {
        try {
            return $this->relations->add($data);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getRelationById(RelationDto $data)
    {
        return $this->relations->getById($data->id);
    }

    public function getRelationByDocAssignId(int $documentId1 = null, int $assignmentId1 = null)
    {
        try {
            $result = [];
            if ($documentId1 != null) {
                $result = $this->relations->listByDocId($documentId1);
                // foreach ($result as $item) {
                //     if ($item['documentId2'] != null) {
                //         $result['relDoc'] = $this->getRelationByDocAssignId($item['documentId2']);
                //     } else if ($item['assignmentId2'] != null) {
                //         $result['relAssign'] = $this->getRelationByDocAssignId(null, $item['assignmentId2']);
                //     }
                // }
            } else if ($assignmentId1 != null) {
                $result = $this->relations->listByAssignId($assignmentId1);
                // if ($result != []) {
                //     foreach ($result as $item) {
                //         if ($item['documentId2'] != null) {
                //             $result['relDoc'] = $this->getRelationByDocAssignId($item['documentId2']);
                //         } else if ($item['assignmentId2'] != null) {
                //             $result['relAssign'] = $this->getRelationByDocAssignId(null, $item['assignmentId2']);
                //         }
                //     }
                // }
            }
            return $result;
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateRelation(RelationDto $data)
    {
        try {
            if ($data->remove == 1) {
                return $this->relations->remove($data->id);
            } else if (isset($data->assignmentId1)) {
                return $this->relations->update($data);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getRelationsList() 
    {
        try {
            return $this->relations->list();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }
}