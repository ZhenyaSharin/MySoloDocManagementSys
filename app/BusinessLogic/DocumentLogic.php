<?php

namespace App\BusinessLogic;

use App\BusinessLogic\FileUploadLogic;
use App\BusinessLogic\UsersLogic;
use App\Dto\AcquaintanceDto\AcquaintanceDto;
use App\Dto\AgreementDto\AgreementDto;
use App\Dto\AgreementDto\AgreementUserDto;
use App\Dto\DocumentDto\DocumentDto;
use App\Dto\OutputDto\PdfDto;
use App\Dto\StatusDto\StatusDto;
use App\Dto\UserDto\UserDto;
use App\Exceptions\BusinessLogicException;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\AcquaintanceRepositoryInterface;
use App\Models\Repositories\Contracts\AgreementsRepositoryInterface;
use App\Models\Repositories\Contracts\AssignmentsRepositoryInterface;
use App\Models\Repositories\Contracts\DocumentsRepositoryInterface;
use App\Models\Repositories\Contracts\UsersRepositoryInterface;
use PDF;
use Throwable;

class DocumentLogic
{

    private $docs;
    private $users;
    private $agrs;
    private $assigns;
    private $files;
    private $acquaintances;
    private $usersLogic;

    public function __construct(DocumentsRepositoryInterface $docs, UsersRepositoryInterface $users, AgreementsRepositoryInterface $agrs, AssignmentsRepositoryInterface $assigns, FileUploadLogic $files, AcquaintanceRepositoryInterface $acquaintances, UsersLogic $usersLogic)
    {
        $this->docs = $docs;
        $this->users = $users;
        $this->agrs = $agrs;
        $this->assigns = $assigns;
        $this->files = $files;
        $this->acquaintances = $acquaintances;
        $this->usersLogic = $usersLogic;
    }

    public function getDeliveryTypesList()
    {
        try {
            return $this->docs->listDeliveryTypes();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getDocumentTypesList()
    {
        try {
            return $this->docs->listDocumentTypes();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getUsersList()
    {
        try {
            return $this->users->list();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getDocumentsList(DocumentDto $data)
    {
        try {
            if ($data->creationDate == 1) {
                return $this->docs->listOrderDate($data->ascDesc);
            }
            return $this->docs->list($data->ascDesc);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function addNewDocument(DocumentDto $data)
    {
        try {
            $result = [];
            if (isset($data->fileId)) {
                if ($data->agreeId == []) {
                    if (isset($data->addFiles)) {
                        $data->addFiles = $this->files->additionFilesCycle($data->addFiles);
                        $result = $this->docs->addWithoutAgreersWithoutFileAddFiles($data);
                    } else {
                        $result = $this->docs->addWithoutAgreersWithoutFile($data);
                    }
                } else {
                    if ($data->orderable != null) {
                        if (isset($data->addFiles)) {
                            $data->addFiles = $this->files->additionFilesCycle($data->addFiles);
                            $result = $this->docs->addWithinAgreersOrderWithoutFileAddFiles($data);
                        } else {
                            $result = $this->docs->addWithinAgreersOrderWithoutFile($data);
                        }
                    } else {
                        if (isset($data->addFiles)) {
                            $data->addFiles = $this->files->additionFilesCycle($data->addFiles);
                            $result = $this->docs->addWithoutFileAddFiles($data);
                        } else {
                            $result = $this->docs->addWithoutFile($data);
                        }
                    }
                }
            } else {
                $fileData = $this->files->fileUpload($data->file);
                if (isset($fileData)) {
                    $data->fileLink = $fileData['file'];
                    $data->fileFormat = $fileData['format'];
                    if ($data->agreeId == []) {
                        if (isset($data->addFiles)) {
                            $data->addFiles = $this->files->additionFilesCycle($data->addFiles);
                            $result = $this->docs->addWithoutAgreersAddFiles($data);
                        } else {
                            $result = $this->docs->addWithoutAgreers($data);
                        }
                    } else {
                        if ($data->orderable != null) {
                            if (isset($data->addFiles)) {
                                $data->addFiles = $this->files->additionFilesCycle($data->addFiles);
                                $result = $this->docs->addWithinAgreersOrderAddFiles($data);
                            } else {
                                $result = $this->docs->addWithinAgreersOrder($data);
                            }
                            if ($result != false) {
                                $mailData = [
                                    'link' => config('app.url') . '/document-' . $result['id'],
                                    'type' => 1,
                                    'desc' => $result['description'],
                                ];
                                $this->usersLogic->sendMail($mailData, 4, $result['emails'][0]['email'], $data->agreeId[0]['id']);
                            }
                        } else {
                            if (isset($data->addFiles)) {
                                $data->addFiles = $this->files->additionFilesCycle($data->addFiles);
                                $result = $this->docs->addAddFiles($data);
                            } else {
                                $result = $this->docs->add($data);
                            }
                            if ($result != false) {
                                foreach ($result['emails'] as $item) {
                                    $mailData = [
                                        'link' => config('app.url') . '/document-' . $result['id'],
                                        'type' => 1,
                                        'desc' => $result['description'],
                                    ];
                                    $this->usersLogic->sendMail($mailData, 4, $item['email'], $item['id']);
                                }
                            }
                        }
                    }
                }
            }
            if (isset($result['acquaintances'])) {
                foreach ($result['acquaintances'] as $item) {
                    $mailData = [
                        'link' => config('app.url') . '/document-' . $result['id'],
                        'type' => 5,
                        'desc' => $result['description'],
                    ];
                    $this->usersLogic->sendMail($mailData, 7, $item['email'], $item['id']);
                }
            }
            return $result;
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException: ' . $e->getMessage());
            }
            throw new BusinessLogicException('BusinessLogicException:' . $e->getMessage());
        }
    }

    public function update(DocumentDto $data)
    {
        try {
            // доделать
            if (isset($data->delete)) {
                return $this->docs->remove($data->id);
            } else {
                return $this->docs->update($data);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getDocsListByUserId(DocumentDto $data)
    {
        try {
            if (isset($data->count)) {
                return $this->docs->listByUserIdWithCount($data->authorId, $data->count, $data->ascDesc);
            } else {
                if ($data->creationDate == 1) {
                    return $this->docs->listByUserIdOrderDate($data->authorId, $data->ascDesc);
                }
                return $this->docs->listByUserId($data->authorId, $data->ascDesc);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getAgreementListByUserId(AgreementUserDto $data)
    {
        try {
            if (isset($data->count)) {
                return $this->agrs->agrListByUserIdWithCount($data->userId, $data->count);
            } else {
                return $this->agrs->agrListByUserId($data->userId);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getDocumentById(DocumentDto $data)
    {
        try {
            if ($data->info != null) {
                $result = $this->docs->getByIdWithInfo($data->id);
                $result['agreements'] = $this->agrs->agreementsByDocId($data->id);
                return $result;
            } else {
                return $this->docs->getById($data->id);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateAgreement(AgreementUserDto $data)
    {
        try {
            if ($data->delete === 1) {
                $result = $this->agrs->removeAgreement($data->agreementId);
            } else {
                if ($data->approve != null) {
                    $result = $this->agrs->approveAgreement($data);
                    if ($result != false) {
                        if (($data->order != null)) {
                            if (isset($result['next'])) {
                                $mailData = [
                                    'link' => config('app.url') . '/document-' . $data->documentId,
                                    'type' => 1,
                                ];
                                $this->usersLogic->sendMail($mailData, 4, $result['next']['user']['email'], $result['next']['user']['id']);
                            }
                        }
                        $mailListData = [
                            'link' => config('app.url') . '/document-' . $data->documentId,
                            'type' => 7,
                            'list' => $result['agreeList'],
                        ];
                        foreach ($result['agreeList'] as $item) {
                            $this->usersLogic->sendMail($mailListData, 10, $item['user']['email'], $item['user']['id']);
                        }
                        $this->usersLogic->sendMail($mailListData, 10, $result['author']['email'], $result['author']['id']);
                    }
                } else if ($data->refusedAt != null) {
                    $result = $this->agrs->refuseAgreement($data);
                    if ($result != false) {
                        $mailData = [
                            'link' => config('app.url') . '/document-' . $data->documentId,
                            'type' => 3,
                            'note' => $data->note,
                            'user' => $result['user']['surname'] . ' ' . mb_substr($result['user']['firstname'], 0, 1) . '. ' . mb_substr($result['user']['patronymic'], 0, 1),
                        ];
                        $this->usersLogic->sendMail($mailData, 9, $result['author']['email'], $result['author']['id']);
                        $mailListData = [
                            'link' => config('app.url') . '/document-' . $data->documentId,
                            'type' => 7,
                            'list' => $result['agreeList'],
                            'refused' => 1,
                        ];
                        foreach ($result['agreeList'] as $item) {
                            $this->usersLogic->sendMail($mailListData, 10, $item['user']['email'], $item['user']['id']);
                        }
                        $this->usersLogic->sendMail($mailListData, 10, $result['author']['email'], $result['author']['id']);
                    }
                } else if ($data->viewed != null) {
                    $result = $this->agrs->makeViewedAgreementUser($data);
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

    public function getAgreementResponsesByUserId(UserDto $data)
    {
        try {
            if (isset($data->count)) {
                return $this->agrs->agrResponsesByUserIdWithLimit($data->id, $data->count);
            } else {
                return $this->agrs->agrResponsesByUserId($data->id);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function addNewAgreementAndUser(AgreementUserDto $data)
    {
        try {
            return $this->agrs->addAgreement($data);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateDocumentIntoArchive(DocumentDto $data)
    {
        try {
            return $this->docs->documentIntoArchive($data->id, $data->statusId);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getAgreementsByDocId(int $id)
    {
        try {
            return $this->agrs->agreementsByDocId($id);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getAgreementsAndUsersHistory(AgreementUserDto $data)
    {
        try {
            if (isset($data->count)) {
                return $this->agrs->agreementsAndUsersHistoryWithCount($data);
            } else {
                return $this->agrs->agreementsAndUsersHistory($data);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    // makeViewedAgreementUser
    public function makePdf(PdfDto $data)
    {
        try {
            $pdf = PDF::loadHTML('<h1>ewfewf</h1>')->setOptions(['defaultFont' => 'times']);
            $pdf->download('pdf_file.pdf');
            return true;
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getCountNonViewedAgreementsAndUsers(AgreementUserDto $data)
    {
        try {
            return $this->agrs->listNonViewedByUserId($data->userId);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getDocsListByTypeId(DocumentDto $data)
    {
        try {
            if (isset($data->count)) {
                if ($data->creationDate == 1) {
                    return $this->docs->listByTypeIdWithCountOrderDate($data->typeId, $data->count, $data->ascDesc);
                }
                return $this->docs->listByTypeIdWithCount($data->typeId, $data->count, $data->ascDesc);
            } else {
                if ($data->creationDate == 1) {
                    return $this->docs->listByTypeIdOrderDate($data->typeId, $data->ascDesc);
                }
                return $this->docs->listByTypeId($data->typeId, $data->ascDesc);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getDocumentVersionsById(int $documentId = null, int $assignmentId = null)
    {
        try {
            if ($documentId != null) {
                $result = $this->docs->getByIdVersions($documentId);
                if ($result['baseId']) {
                    $result['base'] = $this->getDocumentVersionsById($result['baseId']);
                } else if ($result['baseAssignmentId']) {
                    $result['baseAssignment'] = $this->getDocumentVersionsById(null, $result['baseAssignmentId']);
                }
            } else if ($assignmentId != null) {
                $result = $this->assigns->getByIdVersions($assignmentId);
                if ($result['documentId']) {
                    $result['base'] = $this->getDocumentVersionsById($result['documentId']);
                } else if ($result['baseId']) {
                    $result['baseAssignment'] = $this->getDocumentVersionsById(null, $result['baseId']);
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

    public function getAcquaintancesList(AcquaintanceDto $data)
    {
        try {
            if (isset($data->userId)) {
                if (isset($data->notViewed)) {
                    return $this->acquaintances->listByUserIdNonViewed($data->userId);
                } else {
                    return $this->acquaintances->listByUserId($data->userId);
                }
            } elseif (isset($data->initiatorId)) {
                return $this->acquaintances->listByInitiatorId($data->initiatorId);
            } elseif (isset($data->documentId)) {
                return $this->acquaintances->listByDocumentId($data->documentId);
            } else {
                return $this->acquaintances->list();
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function addNewAcquaintances(array $data)
    {
        try {
            $result = $this->acquaintances->add($data);
            if ($result != []) {
                foreach ($result as $item) {
                    $mailData = [
                        'link' => config('app.url') . '/document-' . $item['documentId'],
                        'type' => 5,
                    ];
                    $this->usersLogic->sendMail($mailData, 7, $item['user']['email'], $item['user']['id']);
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

    public function updateAcquaintance(AcquaintanceDto $data)
    {
        try {
            if ($data->view != null) {
                return $this->acquaintances->makeSeen($data);
            } else if ($data->delete != null) {
                return $this->acquaintances->remove($data->id);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getDirusersList()
    {
        try {
            return $this->docs->listDirusers();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getStatuses(StatusDto $data)
    {
        try {
            if ($data->group != null) {
                if ($data->group == 1) {
                    return $this->docs->listDocumentStatuses();
                } else if ($data->group == 2) {
                    return $this->assigns->listAssignStatuses();
                }
            } else {
                return $this->docs->listStatuses();
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateAgreementList(AgreementDto $data)
    {
        try {
            if ($data->orderable != null) {
                if (isset($data->completed)) {
                    if ($data->status == 3) {
                        // при согласованном документе
                        $result = $this->agrs->addAgreementAndUserWithStatusAndOrderCompleted($data);
                        if ($result != false) {
                            if (isset($result[0]['email'])) {
                                $mailData = [
                                    'link' => config('app.url') . '/document-' . $data->documentId,
                                    'type' => 1,
                                ];
                                $this->usersLogic->sendMail($mailData, 4, $result[0]['email']['email'], $data->users[0]->userId);
                            }
                        }
                    } else if ($data->status == 1) {
                        // при документе на рассмотрении
                        $result = $this->agrs->addAgreementAndUserCompleted($data);
                    }
                } else {
                    if ($data->status == 3) {
                        // при согласованном документе
                        $result = $this->agrs->addAgreementAndUserWithStatusAndOrderCompleted($data);
                        if ($result != false) {
                            if (isset($result['email'])) {
                                $mailData = [
                                    'link' => config('app.url') . '/document-' . $data->documentId,
                                    'type' => 1,
                                ];
                                $this->usersLogic->sendMail($mailData, 4, $result['email']['email'], $data->users[0]->userId);
                            }
                        }
                    } else if ($data->status == 1) {
                        // при документе на рассмотрении
                        $result = $this->agrs->addAgreementAndUserWithOrder($data);
                    }
                }
            } else {
                $result = [];
                if ($data->status == 3) {
                    // при согласованном документе
                    $result = $this->agrs->addAgreementAndUserWithStatus($data);
                } else if ($data->status == 1) {
                    // при документе на рассмотрении
                    $result = $this->agrs->addAgreementAndUser($data);
                }
                if ($result != []) {
                    $mailData = [
                        'link' => config('app.url') . '/document-' . $data->id,
                        'type' => 1,
                    ];
                    foreach ($result as $item) {
                        $this->usersLogic->sendMail($mailData, 4, $item['email']['email'], $item['email']['id']);
                    }
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

    public function removeAgreementUser(AgreementUserDto $data)
    {
        try {
            if ($data->docComplete != null) {
                return $this->agrs->removeAgreementUserWithComplete($data->id, $data->docComplete);
            } else {
                return $this->agrs->removeAgreementUser($data->id);
            };
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateDocumentInfo(DocumentDto $data)
    {
        try {
            if (isset($data->diruser)) {
                if (isset($data->diruser['id'])) {
                    return $this->docs->updateInfoWithDiruser($data);
                } else {
                    return $this->docs->updateInfoWithDiruserNew($data);
                }
            } else {
                return $this->docs->updateInfo($data);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getDocumentsListByStatus(DocumentDto $data)
    {
        try {
            if (isset($data->userId)) {
                return $this->docs->listByUserIdAndStatusId($data->userId, $data->statusId);
            } else {
                return $this->docs->listByStatusId($data->statusId);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }
}
