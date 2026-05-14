<?php

namespace App\BusinessLogic;

use App\BusinessLogic\FileUploadLogic;
use App\BusinessLogic\UsersLogic;
use App\Dto\AssignmentDto\AssignmentControlDto;
use App\Dto\AssignmentDto\AssignmentDeadlineDto;
use App\Dto\AssignmentDto\AssignmentDto;
use App\Dto\AssignmentDto\AssignmentExecutorDto;
use App\Dto\AssignmentDto\AssignmentStatusDto;
use App\Exceptions\BusinessLogicException;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\AssignmentsRepositoryInterface;
use Throwable;

class AssignmentLogic
{

    private $assigns;
    private $usersLogic;
    private $fileLogic;

    public function __construct(AssignmentsRepositoryInterface $assigns, UsersLogic $usersLogic, FileUploadLogic $fileLogic)
    {
        $this->assigns = $assigns;
        $this->usersLogic = $usersLogic;
        $this->fileLogic = $fileLogic;
    }

    public function getAssignmentsList()
    {
        try {
            return $this->assigns->listWithInfo();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function addNewAssignment(AssignmentDto $data)
    {
        try {
            if ($data->files != null) {
                foreach ($data->files as &$item) {
                    $fileData = $this->fileLogic->fileUploadAddition($item->file);
                    $item->fileLink = $fileData['file'];
                    $item->format = $fileData['format'];
                }
                $result = $this->assigns->addWithAddition($data);
            } else {
                $result = $this->assigns->add($data);
            }
            if ($result != []) {
                foreach ($result as $item) {
                    $mailData = [
                        'link' => config('app.url') . '/assignment-' . $item['id'],
                        'type' => 2,
                        'desc' => $item['text'],
                    ];
                    $this->usersLogic->sendMail($mailData, 5, $item['email']['email'], $item['email']['id']);
                }
            }
            return $result;
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getAssignmentTypes()
    {
        try {
            return $this->assigns->listAssignmentTypes();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getAssignmentsByAuthorId(AssignmentDto $data)
    {
        try {
            if ($data->count != null) {
                return $this->assigns->listByAuthorIdWithCount($data->authorId, $data->count);
            } else {
                return $this->assigns->listByAuthorId($data->authorId);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getAssignmentsByExecutorId(AssignmentExecutorDto $data)
    {
        try {
            if ($data->count != null) {
                return $this->assigns->listByExecutorIdWithCount($data->executorId, $data->count);
            } else {
                return $this->assigns->listByExecutorId($data->executorId);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateAssignment(AssignmentDto $data)
    {
        try {
            if ($data->viewed != null) {
                return $this->assigns->makeViewedAssignment($data);
            } else if ($data->delete != null) {
                return $this->assigns->removeAssignment($data);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getAssignmentById(AssignmentDto $data)
    {
        try {
            if ($data->info == 1) {
                return $this->assigns->getByIdWithInfo($data->id);
            } else {
                return $this->assigns->getById($data->id);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getNonViewedAssignmentsByExecutorId(AssignmentExecutorDto $data)
    {
        try {
            return $this->assigns->listNonViewedByExecutorId($data->executorId);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function newDeadlineAssignment(AssignmentDto $data)
    {
        try {
            return $this->assigns->newDeadline($data);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getListByMainId(AssignmentDto $data)
    {
        try {
            return $this->assigns->listByMainId($data->id);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateAssignmentStatus(AssignmentStatusDto $data)
    {
        try {
            if ($data->deadline) {
                return $this->assigns->updateStatusWithNewDeadline($data);
            } else {
                if ($data->alias) {
                    return $this->assigns->updateStatusByAlias($data);
                } else if ($data->statusId) {
                    return $this->assigns->updateStatus($data);
                }
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getAssignmentStatusLog(AssignmentStatusDto $data)
    {
        try {
            return $this->assigns->listLog($data->assignmentId);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function addNewControl(AssignmentControlDto $data)
    {
        try {
            $result = $this->assigns->addControl($data);
            if ($result != false) {
                $mailData = [
                    'link' => config('app.url') . '/assignment-' . $result['assignmentId'],
                    'type' => 4,
                ];
                $this->usersLogic->sendMail($mailData, 8, $result['user']['email'], $result['user']['id']);
            }
            return $result;
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateControl(AssignmentControlDto $data)
    {
        try {
            if ($data->viewed != null) {
                return $this->assigns->viewControl($data->id);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getAssignmentControls(AssignmentControlDto $data)
    {
        try {
            if ($data->userId != null) {
                return $this->assigns->controlsByUserId($data->userId);
            } else if ($data->assignmentId != null) {
                return $this->assigns->controlLastByAssignmentId($data->assignmentId);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }
    // updateAssignmentDeadline

    public function updateAssignmentDeadline(AssignmentDeadlineDto $data)
    {
        try {
            if ($data->approve != null) {
                return $this->assigns->approveDeadline($data->id, $data->approvedUserId);
            } else if ($data->refuse != null) {
                return $this->assigns->refuseDeadline($data->id, $data->approvedUserId);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function addAssignmentDeadline(AssignmentDeadlineDto $data)
    {
        try {
            $result = $this->assigns->addDeadline($data);
            if ($result != false) {
                if ($result['author'] != false) {
                    $mailData = [
                        'link' => config('app.url') . '/assignment-' . $data->assignmentId,
                        'type' => 6,
                        'user' => $result['initiator']['surname'] . ' ' . mb_substr($result['initiator']['firstname'], 0, 1) . '. ' . mb_substr($result['initiator']['patronymic'], 0, 1) . '. ',
                        'deadline' => $data->deadline,
                    ];
                    $this->usersLogic->sendMail($mailData, 6, $result['author']['email'], $result['author']['id']);

                }
                return $result;
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateAssignmentInfo(AssignmentDto $data)
    {
        try {
            return $this->assigns->updateInfo($data);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }
}
