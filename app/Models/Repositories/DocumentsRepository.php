<?php

namespace App\Models\Repositories;

use App\Dto\DocumentDto\DocumentDto;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\DocumentsRepositoryInterface;
use Illuminate\Support\Facades\DB;
use PDO;
use Throwable;

class DocumentsRepository implements DocumentsRepositoryInterface
{
    public function listDeliveryTypes()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT "id", "title" FROM "GetDeliveryTypes"() WHERE "removed" IS NULL');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }

            throw new DatabaseException($e->getMessage());
        }
    }

    public function listDocumentTypes()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT "id", "title" FROM "GetDocumentTypes"() WHERE "removed" IS NULL');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }

            throw new DatabaseException($e->getMessage());
        }
    }

    public function list(string $ascDesc = 'DESC')
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllDocuments"() WHERE "removed" IS NULL ORDER BY "id"'. $ascDesc);
            // $stm->bindValue("FltOrder", $ascDesc);
            // echo($ascDesc);
            $stm->execute();
            $result = [];
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($res) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT * FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "GetDiruserAndDocumentByDocumentId"(:FltId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDiruserUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetDiruserById"(:FltDiruserId)');
                foreach ($res as &$item) {
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                    $stmStatus->bindValue("FltDocumentId", $item["id"]);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    $stmDiruserDoc->bindValue("FltId", $item['id']);
                    $stmDiruserDoc->execute();
                    $item['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    if ($item['diruser'] != false) {
                        $stmDiruserUser->bindValue("FltDiruserId", $item['diruser']['diruserId']);
                        $stmDiruserUser->execute();
                        $item['diruser']['user'] = $stmDiruserUser->fetch(PDO::FETCH_ASSOC);
                    };
                    // if (($item['status']['docstatusId'] != 4) && ($item['status']['docstatusId'] != 2)) {
                    if ($item['status']['docstatusId'] != 4) {
                        $stmAuthor->bindValue("FltAuthorId", $item["authorId"]);
                        $stmAuthor->execute();
                        $item['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                        $result[] = $item;
                    };
                };
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listOfRefusedWithNote()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllDocuments"() WHERE "removed" IS NULL ORDER BY "id" DESC');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT * FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                $stmAgr = $pdo->prepare('SELECT "id" FROM "GetAgreementByDocumentId"(:FltDocumentId)');
                foreach ($result as &$item) {
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                    $stmStatus->bindValue("FltDocumentId", $item["id"]);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    $stmAuthor->bindValue("FltAuthorId", $item["authorId"]);
                    $stmAuthor->execute();
                    $item['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function add(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                $stmFile->bindValue("FltFile", $data->fileLink);
                $stmFile->bindValue("FltFormat", $data->fileFormat);
                $stmFile->bindValue("FltType", 1);
                $stmFile->bindValue("FltComment", null);
                $stmFile->execute();
                $file = $stmFile->fetch(PDO::FETCH_ASSOC);

                if ($file != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $file['id']);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file'] = $file;
                    $result['file']['fileDocId'] = $fileDoc['id'];
                }
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmMail = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltId)');
                    $result['agreementUserId'] = [];
                    $result['emails'] = [];
                    foreach ($data->agreeId as $item) {
                        $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                        $stmUser->bindValue("FltUserId", $item['id']);
                        $stmUser->bindValue("FltOrder", null);
                        $stmUser->execute();
                        $result['agreementUserId'][] = $stmUser->fetch(PDO::FETCH_ASSOC);
                        $stmMail->bindValue("FltId", $item['id']);
                        $stmMail->execute();
                        $result['emails'][] = $stmMail->fetch(PDO::FETCH_ASSOC);
                    }
                }
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 1);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    // Рассылка начальству
                    $result['roleId'] = null;
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as &$item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $item['email'] = $leaders['email'];
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addWithoutAgreers(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                $stmFile->bindValue("FltFile", $data->fileLink);
                $stmFile->bindValue("FltFormat", $data->fileFormat);
                $stmFile->bindValue("FltType", 1);
                $stmFile->bindValue("FltComment", null);
                $stmFile->execute();
                $file = $stmFile->fetch(PDO::FETCH_ASSOC);

                if ($file != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $file['id']);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file'] = $file;
                    $result['file']['fileDocId'] = $fileDoc['id'];
                }
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                    $stmUser->bindValue("FltUserId", $data->authorId);
                    $stmUser->bindValue("FltOrder", null);
                    $stmUser->execute();

                    $result['agreementUserId'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                    if ($result['agreementUserId'] != false) {
                        $stmApprove = $pdo->prepare('SELECT * FROM "ApproveAgreementsAndUsersById"(:FltAgreementUserId)');
                        $stmApprove->bindValue("FltAgreementUserId", $result['agreementUserId']['id']);
                        $stmApprove->execute(); //wfeefwefewf
                        $result['agreementApproved'] = $stmApprove->fetch(PDO::FETCH_ASSOC);

                        $stmTotal = $pdo->prepare('SELECT * FROM "ApproveAgreementById"(:FltAgreementId)');
                        $stmTotal->bindValue("FltAgreementId", $result['agreementId']['id']);
                        $stmTotal->execute();
                        $result['agreement'] = $stmTotal->fetch(PDO::FETCH_ASSOC);
                    }
                }

                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 3);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }
                // Рассылка начальству
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as $item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addWithinAgreersOrder(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                $stmFile->bindValue("FltFile", $data->fileLink);
                $stmFile->bindValue("FltFormat", $data->fileFormat);
                $stmFile->bindValue("FltType", 1);
                $stmFile->bindValue("FltComment", null);
                $stmFile->execute();
                $file = $stmFile->fetch(PDO::FETCH_ASSOC);

                if ($file != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $file['id']);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file'] = $file;
                    $result['file']['fileDocId'] = $fileDoc['id'];
                }
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmUserNoDate = $pdo->prepare('SELECT * FROM "AddAgreementUserWithoutCreatedAt"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $result['agreementUserId'] = [];
                    $stmMail = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltId)');
                    $result['emails'] = [];
                    for ($i = 0; $i < count($data->agreeId); $i++) {
                        if ($i === 0) {
                            $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                            $stmUser->bindValue("FltUserId", $data->agreeId[$i]['id']);
                            $stmUser->bindValue("FltOrder", $i + 1);
                            $stmUser->execute();
                            $resUser = $stmUser->fetch(PDO::FETCH_ASSOC);
                        } else {
                            $stmUserNoDate->bindValue("FltAgreementId", $result['agreementId']['id']);
                            $stmUserNoDate->bindValue("FltUserId", $data->agreeId[$i]['id']);
                            $stmUserNoDate->bindValue("FltOrder", $i + 1);
                            $stmUserNoDate->execute();
                            $resUser = $stmUserNoDate->fetch(PDO::FETCH_ASSOC);
                        }
                        $result['agreementUserId'][] = $resUser;
                        $stmMail->bindValue("FltId", $data->agreeId[$i]['id']);
                        $stmMail->execute();
                        $result['emails'][] = $stmMail->fetch(PDO::FETCH_ASSOC);
                    }
                }
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 1);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }
                // Рассылка начальству
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as &$item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $item['email'] = $leaders['email'];
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function getById(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltId)');
            $stm->bindValue("FltId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function getByIdVersions(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltId)');
            $stm->bindValue("FltId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if (isset($result)) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                $stmType->bindValue("FltId", $result["typeId"]);
                $stmType->execute();
                $result['type'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                $stmStatus->bindValue("FltDocumentId", $result["id"]);
                $stmStatus->execute();

                $result['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                $stmAuthor->bindValue("FltUserId", $result["authorId"]);

                $stmAuthor->execute();
                $result['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function getByIdWithInfo(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltId)');
            $stm->bindValue("FltId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if (isset($result)) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT * FROM "GetDocStatusLogByDocumentId"(:FltDocumentId)');
                $stmDelivery = $pdo->prepare('SELECT "id", "title" FROM "GetDeliveryTypes"() WHERE "id" = :FltDeliveryId');
                $stmAuthor = $pdo->prepare('SELECT * FROM "GetUserById"(:FltUserId)');
                $stmType->bindValue("FltId", $result["typeId"]);
                $stmType->execute();
                $result['type'] = $stmType->fetch(PDO::FETCH_ASSOC);

                $stmStatus->bindValue("FltDocumentId", $result["id"]);
                $stmStatus->execute();
                $result['status'] = $stmStatus->fetchAll(PDO::FETCH_ASSOC);

                $stmDelivery->bindValue("FltDeliveryId", $result["deliveryId"]);
                $stmDelivery->execute();
                $result['deliveryType'] = $stmDelivery->fetch(PDO::FETCH_ASSOC);

                $stmAuthor->bindValue("FltUserId", $result["authorId"]);
                $stmAuthor->execute();
                $result['authorData'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);

                if ($result['recorderId'] != $result['authorId']) {
                    $stmRecorder = $pdo->prepare('SELECT * FROM "GetUserById"(:FltRecorderId)');
                    $stmRecorder->bindValue("FltRecorderId", $result["recorderId"]);
                    $stmRecorder->execute();
                    $result['recorder'] = $stmRecorder->fetch(PDO::FETCH_ASSOC);
                }

                if ($result['baseId'] != null) {
                    $stmBaseDoc = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltDocId) WHERE "removed" IS NULL');
                    $stmBaseDoc->bindValue("FltDocId", $result["baseId"]);
                    $stmBaseDoc->execute();
                    $result['baseDoc'] = $stmBaseDoc->fetch(PDO::FETCH_ASSOC);
                    $stmBaseDocStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocId) LIMIT 1 ');
                    $stmBaseDocStatus->bindValue("FltDocId", $result["baseId"]);
                    $stmBaseDocStatus->execute();
                    $result['baseDoc']['status'] = $stmBaseDocStatus->fetch(PDO::FETCH_ASSOC);
                }

                if ($result['baseAssignmentId'] != null) {
                    $stmBaseAssignment = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltAssignId) WHERE "removed" IS NULL');
                    $stmBaseAssignment->bindValue("FltAssignId", $result['baseAssignmentId']);
                    $stmBaseAssignment->execute();
                    $result['baseAssignment'] = $stmBaseAssignment->fetch(PDO::FETCH_ASSOC);
                    $stmBAAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                    $stmBAAuthor->bindValue("FltUserId", $result['baseAssignment']['authorId']);
                    $stmBAAuthor->execute();
                    $result['baseAssignment']['author'] = $stmBAAuthor->fetch(PDO::FETCH_ASSOC);
                    $stmBAStatusId = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                    $stmBAStatusId->bindValue("FltAssignId", $result['baseAssignment']['id']);
                    $stmBAStatusId->execute();
                    $statusId = $stmBAStatusId->fetch(PDO::FETCH_ASSOC);
                    $stmBAStatusData = $pdo->prepare('SELECT * FROM "GetStatusById"(:FltId) WHERE "removed" IS NULL');
                    $stmBAStatusData->bindValue("FltId", $statusId['assignmentstatusId']);
                    $stmBAStatusData->execute();
                    $result['baseAssignment']['status'] = $stmBAStatusData->fetch(PDO::FETCH_ASSOC);
                }

                if ($result['linkedDocId'] != null) {
                    $stmlinkedDocId = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltDocId) WHERE "removed" IS NULL');
                    $stmlinkedDocId->bindValue("FltDocId", $result["linkedDocId"]);
                    $stmlinkedDocId->execute();
                    $result['baseDoc'] = $stmlinkedDocId->fetch(PDO::FETCH_ASSOC);
                }
                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "GetDiruserAndDocumentByDocumentId"(:FltId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDiruserDoc->bindValue("FltId", $result['id']);
                $stmDiruserDoc->execute();
                $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                if ($result['diruser'] != false) {
                    $stmDiruserUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetDiruserById"(:FltDiruserId)');
                    $stmDiruserUser->bindValue("FltDiruserId", $result['diruser']['diruserId']);

                    $stmDiruserUser->execute();
                    $result['diruser']['user'] = $stmDiruserUser->fetch(PDO::FETCH_ASSOC);
                }
                $stmDocFile = $pdo->prepare('SELECT * FROM "GetDocumentAndFileByDocumentId"(:FltDocumentId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDocFile->bindValue("FltDocumentId", $id);
                $stmDocFile->execute();
                $docFile = $stmDocFile->fetch(PDO::FETCH_ASSOC);
                if ($docFile != false) {
                    $stmFile = $pdo->prepare('SELECT * FROM "GetFileById"(:FltId)');
                    $stmFile->bindValue("FltId", $docFile['fileId']);
                    $stmFile->execute();
                    $result['file'] = $stmFile->fetch(PDO::FETCH_ASSOC);
                }
                $stmAddFile = $pdo->prepare('SELECT * FROM "GetFileAndAdditionByDocumentId"(:FltDocumentId) WHERE "removed" IS NULL ORDER BY "created_at" DESC');
                $stmAddFile->bindValue("FltDocumentId", $id);
                $stmAddFile->execute();
                $addFiles = $stmAddFile->fetchAll(PDO::FETCH_ASSOC);
                $result['fileAddition'] = [];
                if ($addFiles != []) {
                    foreach ($addFiles as $item) {
                        $stmAddFileFile = $pdo->prepare('SELECT * FROM "GetFileById"(:FltId)');
                        $stmAddFileFile->bindValue("FltId", $item['fileId']);
                        $stmAddFileFile->execute();
                        $file = $stmAddFileFile->fetch(PDO::FETCH_ASSOC);
                        $file['addFile'] = $item;
                        $result['fileAddition'][] = $file;
                    }
                }
                if ($result['executor'] != null) {
                    $stmExecutor = $pdo->prepare('SELECT * FROM "GetUserById"(:FltExecutorId)');
                    $stmExecutor->bindValue("FltExecutorId", $result["executor"]);
                    $stmExecutor->execute();
                    $result['executorUser'] = $stmExecutor->fetch(PDO::FETCH_ASSOC);
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listByUserId(int $id, string $ascDesc = 'DESC')
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocsByUserId"(:FltUserId) WHERE "removed" IS NULL ORDER BY "created_at" ' . $ascDesc);
            $stm->bindValue("FltUserId", $id);
            // $stm->bindValue("FltOrder", $ascDesc);
            $stm->execute();
            $result = [];
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($res) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1 ');
                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "GetDiruserAndDocumentByDocumentId"(:FltId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDiruserUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetDiruserById"(:FltDiruserId)');
                foreach ($res as &$item) {
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                    $stmStatus->bindValue("FltDocumentId", $item["id"]);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    $stmDiruserDoc->bindValue("FltId", $item['id']);
                    $stmDiruserDoc->execute();
                    $item['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    if ($item['diruser'] != false) {
                        $stmDiruserUser->bindValue("FltDiruserId", $item['diruser']['diruserId']);

                        $stmDiruserUser->execute();
                        $item['diruser']['user'] = $stmDiruserUser->fetch(PDO::FETCH_ASSOC);
                    };
                    // if ($item['status']['docstatusId'] != 4) {
                    if (($item['status']['docstatusId'] != 4) && ($item['status']['docstatusId'] != 2)) {
                        $result[] = $item;
                    };
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listByTypeId(int $typeId, string $ascDesc = 'DESC')
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocsByTypeId"(:FltTypeId) WHERE "removed" IS NULL ORDER BY "created_at" ' . $ascDesc);
            $stm->bindValue("FltTypeId", $typeId);
            // $stm->bindValue("FltOrder", $ascDesc);
            $stm->execute();
            $result = [];
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($res) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1 ');

                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "GetDiruserAndDocumentByDocumentId"(:FltId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDiruserUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetDiruserById"(:FltDiruserId)');
                foreach ($res as &$item) {
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                    $stmStatus->bindValue("FltDocumentId", $item["id"]);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    $stmDiruserDoc->bindValue("FltId", $item['id']);
                    $stmDiruserDoc->execute();
                    $item['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    if ($item['diruser'] != false) {
                        $stmDiruserUser->bindValue("FltDiruserId", $item['diruser']['diruserId']);

                        $stmDiruserUser->execute();
                        $item['diruser']['user'] = $stmDiruserUser->fetch(PDO::FETCH_ASSOC);
                    };
                    // if ($item['status']['docstatusId'] != 4) {
                    if (($item['status']['docstatusId'] != 4) && ($item['status']['docstatusId'] != 2)) {
                        $result[] = $item;
                    };
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listByTypeIdWithCount(int $typeId, int $count, string $ascDesc = 'DESC')
    {
        try {
            // echo 'string';
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocsByTypeId"(:FltTypeId) WHERE "removed" IS NULL ORDER BY "created_at" '.$ascDesc.' LIMIT '.$count);
            $stm->bindValue("FltTypeId", $typeId);
            // $stm->bindValue("FltCount", $count);
            // $stm->bindValue("FltOrder", $ascDesc);
            $stm->execute();
            $res = [];
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1 ');

                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "GetDiruserAndDocumentByDocumentId"(:FltId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDiruserUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetDiruserById"(:FltDiruserId)');
                foreach ($result as &$item) {
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                    $stmStatus->bindValue("FltDocumentId", $item["id"]);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    // if ($item['status']['docstatusId'] != 4) {
                    if (($item['status']['docstatusId'] != 4) && ($item['status']['docstatusId'] != 2)) {
                        $stmDiruserDoc->bindValue("FltId", $item['id']);
                        $stmDiruserDoc->execute();
                        $item['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        if ($item['diruser'] != false) {
                            $stmDiruserUser->bindValue("FltDiruserId", $item['diruser']['diruserId']);
                            $stmDiruserUser->execute();
                            $item['diruser']['user'] = $stmDiruserUser->fetch(PDO::FETCH_ASSOC);
                    };
                    // $res[] = $item;
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listByUserIdWithCount(int $id, int $count, string $ascDesc = 'DESC')
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocsByUserId"(:FltUserId) WHERE "removed" IS NULL ORDER BY "created_at" '.$ascDesc.' LIMIT ' . $count);
            $stm->bindValue("FltUserId", $id);
            // $stm->bindValue("FltCount", $count);
            // $stm->bindValue("FltOrder", $ascDesc);
            $stm->execute();
            $res = [];
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1');
                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "GetDiruserAndDocumentByDocumentId"(:FltId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDiruserUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetDiruserById"(:FltDiruserId)');
                foreach ($result as &$item) {
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                    $stmStatus->bindValue("FltDocumentId", $item["id"]);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    $stmDiruserDoc->bindValue("FltId", $item['id']);
                    $stmDiruserDoc->execute();
                    $item['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    if ($item['diruser'] != false) {
                        $stmDiruserUser->bindValue("FltDiruserId", $item['diruser']['diruserId']);

                        $stmDiruserUser->execute();
                        $item['diruser']['user'] = $stmDiruserUser->fetch(PDO::FETCH_ASSOC);
                    };
                    // if ($item['status']['docstatusId'] != 4) {
                    //     $res[] = $item;
                    // }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function documentIntoArchive(int $id, int $statusId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocumentId, :FltStatusId)');
            $stm->bindValue("FltDocumentId", $id);
            $stm->bindValue("FltStatusId", $statusId);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function update(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdateDocument"(:FltDocumentId, :FltStatusId)');
            $stm->bindValue("FltDocumentId", $id);
            $stm->bindValue("FltStatusId", 4);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function remove(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RemoveDocument"(:FltId)');
            $stm->bindValue("FltId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocumentId, :FltStatusId)');
                $stmStatus->bindValue("FltDocumentId", $id);
                $stmStatus->bindValue("FltStatusId", 5);
                $stmStatus->execute();
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listDirusers()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllDirusers"() WHERE "removed" IS NULL ORDER BY "surname" ASC');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }

            throw new DatabaseException($e->getMessage());
        }
    }

    public function listDepartmentsWithHeads()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllDepartments"() WHERE "removed" IS NULL');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }

            throw new DatabaseException($e->getMessage());
        }
    }

    public function search(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllDepartments"() WHERE "removed" IS NULL');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }

            throw new DatabaseException($e->getMessage());
        }
    }

    public function listDocumentStatuses()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetStatusesByGroup"(:FltGroup) WHERE "removed" IS NULL ORDER BY "id"');
            $stm->bindValue("FltGroup", 1);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }

            throw new DatabaseException($e->getMessage());
        }
    }

    public function listStatuses()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllStatuses"() WHERE "removed" IS NULL ORDER BY "id"');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }

            throw new DatabaseException($e->getMessage());
        }
    }

    public function updateInfo(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdateDocumentInfo"(:FltDocumentId, :FltDescription, :FltOrderNum, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltSignatory, :FltExecutor, :FltLetterExecutor, :FltOuterNum, :FltOuterDate, :FltBaseId, :FltBaseAssignmentId)');
            $stm->bindValue("FltDocumentId", $data->id);
            $stm->bindValue("FltDescription", $data->description ?? null);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }

            throw new DatabaseException($e->getMessage());
        }
    }

    public function updateInfoWithDiruser(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdateDocumentInfo"(:FltDocumentId, :FltDescription, :FltOrderNum, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltSignatory, :FltExecutor, :FltLetterExecutor, :FltOuterNum, :FltOuterDate, :FltBaseId, :FltBaseAssignmentId)');
            $stm->bindValue("FltDocumentId", $data->id);
            $stm->bindValue("FltDescription", $data->description ?? null);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if (isset($data->diruser['id'])) {
                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                $stmDiruserDoc->bindValue("FltDocId", $data->id);
                $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                $stmDiruserDoc->execute();
                $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }

            throw new DatabaseException($e->getMessage());
        }
    }

    public function updateInfoWithDiruserNew(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdateDocumentInfo"(:FltDocumentId, :FltDescription, :FltOrderNum, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltSignatory, :FltExecutor, :FltLetterExecutor, :FltOuterNum, :FltOuterDate, :FltBaseId, :FltBaseAssignmentId)');
            $stm->bindValue("FltDocumentId", $data->id);
            $stm->bindValue("FltDescription", $data->description ?? null);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
            $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
            $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
            $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
            $stmDiruser->bindValue("FltDepartmentId", 1);
            $stmDiruser->execute();
            $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
            if (isset($diruser)) {
                $result['diruser'] = $diruser;
                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                $stmDiruserDoc->bindValue("FltDocId", $data->id);
                $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                $stmDiruserDoc->execute();
                $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }

            throw new DatabaseException($e->getMessage());
        }
    }

    public function listByStatusId(int $statusId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllDocuments"() WHERE "removed" IS NULL ORDER BY "id" DESC');
            $stm->execute();
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            if (count($res) > 0) {
                $stmStatus = $pdo->prepare('SELECT * FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1');
                $stmType = $pdo->prepare('SELECT "id", "title" FROM "GetDocTypeById"(:FltId)');
                foreach ($res as &$item) {
                    $stmStatus->bindValue("FltDocumentId", $item['id']);
                    $stmStatus->execute();
                    $arch = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    if ($arch['docstatusId'] === $statusId) {
                        $item['status'] = $arch;
                        $stmType->bindValue("FltId", $item["typeId"]);
                        $stmType->execute();
                        $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                        $result[] = $item;
                    }
                }
            };
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listByUserIdAndStatusId(int $userId, int $statusId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocsByUserId"(:FltUserId) WHERE "removed" IS NULL ORDER BY "created_at" DESC');
            $stm->bindValue("FltUserId", $userId);
            $stm->execute();
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            if (count($res) > 0) {
                $stmStatus = $pdo->prepare('SELECT * FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1');
                $stmType = $pdo->prepare('SELECT "id", "title" FROM "GetDocTypeById"(:FltId)');
                foreach ($res as &$item) {
                    $stmStatus->bindValue("FltDocumentId", $item['id']);
                    $stmStatus->execute();
                    $arch = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    if ($arch['docstatusId'] === $statusId) {
                        $item['status'] = $arch;
                        $stmType->bindValue("FltId", $item["typeId"]);
                        $stmType->execute();
                        $result[] = $item;
                    }
                }
            };
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addWithoutAgreersWithoutFile(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                if ($data->fileId != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $data->fileId);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file']['fileDocId'] = $fileDoc['id'];
                }
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                    $stmUser->bindValue("FltUserId", $data->authorId);
                    $stmUser->bindValue("FltOrder", null);
                    $stmUser->execute();

                    $result['agreementUserId'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                    if ($result['agreementUserId'] != false) {
                        $stmApprove = $pdo->prepare('SELECT * FROM "ApproveAgreementsAndUsersById"(:FltAgreementUserId)');
                        $stmApprove->bindValue("FltAgreementUserId", $result['agreementUserId']['id']);
                        $stmApprove->execute(); //wfeefwefewf
                        $result['agreementApproved'] = $stmApprove->fetch(PDO::FETCH_ASSOC);

                        $stmTotal = $pdo->prepare('SELECT * FROM "ApproveAgreementById"(:FltAgreementId)');
                        $stmTotal->bindValue("FltAgreementId", $result['agreementId']['id']);
                        $stmTotal->execute();
                        $result['agreement'] = $stmTotal->fetch(PDO::FETCH_ASSOC);
                    }
                }

                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 3);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }
                // Рассылка начальству
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as &$item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $item['email'] = $leaders['email'];
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addWithinAgreersOrderWithoutFile(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                if ($data->fileId != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $data->fileId);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file']['fileDocId'] = $fileDoc['id'];
                }
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmUserNoDate = $pdo->prepare('SELECT * FROM "AddAgreementUserWithoutCreatedAt"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $result['agreementUserId'] = [];
                    $stmMail = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltId)');
                    $result['emails'] = [];
                    for ($i = 0; $i < count($data->agreeId); $i++) {
                        if ($i === 0) {
                            $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                            $stmUser->bindValue("FltUserId", $data->agreeId[$i]['id']);
                            $stmUser->bindValue("FltOrder", $i + 1);
                            $stmUser->execute();
                            $resUser = $stmUser->fetch(PDO::FETCH_ASSOC);
                        } else {
                            $stmUserNoDate->bindValue("FltAgreementId", $result['agreementId']['id']);
                            $stmUserNoDate->bindValue("FltUserId", $data->agreeId[$i]['id']);
                            $stmUserNoDate->bindValue("FltOrder", $i + 1);
                            $stmUserNoDate->execute();
                            $resUser = $stmUserNoDate->fetch(PDO::FETCH_ASSOC);
                        }
                        $result['agreementUserId'][] = $resUser;
                        $stmMail->bindValue("FltId", $data->agreeId[$i]['id']);
                        $stmMail->execute();
                        $result['emails'][] = $stmMail->fetch(PDO::FETCH_ASSOC);
                    }
                }
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 1);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }

                // Рассылка начальству
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as &$item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $item['email'] = $leaders['email'];
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addWithoutFile(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                if ($data->fileId != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $data->fileId);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file']['fileDocId'] = $fileDoc['id'];
                };
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmMail = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltId)');
                    $result['agreementUserId'] = [];
                    $result['emails'] = [];
                    foreach ($data->agreeId as $item) {
                        $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                        $stmUser->bindValue("FltUserId", $item['id']);
                        $stmUser->bindValue("FltOrder", null);
                        $stmUser->execute();
                        $result['agreementUserId'][] = $stmUser->fetch(PDO::FETCH_ASSOC);
                        $stmMail->bindValue("FltId", $item['id']);
                        $stmMail->execute();
                        $result['emails'][] = $stmMail->fetch(PDO::FETCH_ASSOC);
                    }
                }
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 1);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }

                // Рассылка начальству
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as &$item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $item['email'] = $leaders['email'];
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listOrderDate(string $ascDesc = 'DESC')
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllDocuments"() WHERE "removed" IS NULL ORDER BY "creationDate" ' . $ascDesc . ' NULLS LAST');
            // $stm->bindValue("FltOrder", $ascDesc);
            $stm->execute();
            $result = [];
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($res) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT * FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "GetDiruserAndDocumentByDocumentId"(:FltId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDiruserUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetDiruserById"(:FltDiruserId)');
                foreach ($res as &$item) {
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                    $stmStatus->bindValue("FltDocumentId", $item["id"]);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    $stmDiruserDoc->bindValue("FltId", $item['id']);
                    $stmDiruserDoc->execute();
                    $item['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    if ($item['diruser'] != false) {
                        $stmDiruserUser->bindValue("FltDiruserId", $item['diruser']['diruserId']);
                        $stmDiruserUser->execute();
                        $item['diruser']['user'] = $stmDiruserUser->fetch(PDO::FETCH_ASSOC);
                    };
                    // if ($item['status']['docstatusId'] != 4) {
                    if (($item['status']['docstatusId'] != 4) && ($item['status']['docstatusId'] != 2)) {
                        $stmAuthor->bindValue("FltAuthorId", $item["authorId"]);
                        $stmAuthor->execute();
                        $item['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                        $result[] = $item;
                    };
                };
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listByUserIdOrderDate(int $id, string $ascDesc = 'DESC')
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocsByUserId"(:FltUserId) WHERE "removed" IS NULL ORDER BY "creationDate" ' . $ascDesc . ' NULLS LAST');
            $stm->bindValue("FltUserId", $id);
            // $stm->bindValue("FltOrder", $ascDesc);
            $stm->execute();
            $result = [];
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($res) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1 ');
                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "GetDiruserAndDocumentByDocumentId"(:FltId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDiruserUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetDiruserById"(:FltDiruserId)');
                foreach ($res as &$item) {
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                    $stmStatus->bindValue("FltDocumentId", $item["id"]);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    $stmDiruserDoc->bindValue("FltId", $item['id']);
                    $stmDiruserDoc->execute();
                    $item['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    if ($item['diruser'] != false) {
                        $stmDiruserUser->bindValue("FltDiruserId", $item['diruser']['diruserId']);

                        $stmDiruserUser->execute();
                        $item['diruser']['user'] = $stmDiruserUser->fetch(PDO::FETCH_ASSOC);
                    };
                    // if ($item['status']['docstatusId'] != 4) {
                    if (($item['status']['docstatusId'] != 4) && ($item['status']['docstatusId'] != 2)) {
                        $result[] = $item;
                    };
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listByTypeIdOrderDate(int $typeId, string $ascDesc = 'DESC')
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocsByTypeId"(:FltTypeId) WHERE "removed" IS NULL ORDER BY "creationDate" ' . $ascDesc . ' NULLS LAST');
            $stm->bindValue("FltTypeId", $typeId);
            // $stm->bindValue("FltOrder", $ascDesc);
            $stm->execute();
            $result = [];
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($res) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1 ');

                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "GetDiruserAndDocumentByDocumentId"(:FltId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDiruserUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetDiruserById"(:FltDiruserId)');
                foreach ($res as &$item) {
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                    $stmStatus->bindValue("FltDocumentId", $item["id"]);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    $stmDiruserDoc->bindValue("FltId", $item['id']);
                    $stmDiruserDoc->execute();
                    $item['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    if ($item['diruser'] != false) {
                        $stmDiruserUser->bindValue("FltDiruserId", $item['diruser']['diruserId']);

                        $stmDiruserUser->execute();
                        $item['diruser']['user'] = $stmDiruserUser->fetch(PDO::FETCH_ASSOC);
                    };
                    // if ($item['status']['docstatusId'] != 4) {
                    if (($item['status']['docstatusId'] != 4) && ($item['status']['docstatusId'] != 2)) {
                        $result[] = $item;
                    };
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listByTypeIdWithCountOrderDate(int $typeId, int $count, string $ascDesc = 'DESC')
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocsByTypeId"(:FltTypeId) WHERE "removed" IS NULL ORDER BY "creationDate" ' . $ascDesc . ' NULLS LAST LIMIT '.$count);
            $stm->bindValue("FltTypeId", $typeId);
            // $stm->bindValue("FltOrder", $ascDesc);
            // $stm->bindValue("FltCount", $count);
            $stm->execute();
            $res = [];
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1 ');

                $stmDiruserDoc = $pdo->prepare('SELECT * FROM "GetDiruserAndDocumentByDocumentId"(:FltId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDiruserUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetDiruserById"(:FltDiruserId)');
                foreach ($result as &$item) {
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                    $stmStatus->bindValue("FltDocumentId", $item["id"]);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    // if ($item['status']['docstatusId'] != 4) {
                    if (($item['status']['docstatusId'] != 4) && ($item['status']['docstatusId'] != 2)) {
                        $stmDiruserDoc->bindValue("FltId", $item['id']);
                        $stmDiruserDoc->execute();
                        $item['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        if ($item['diruser'] != false) {
                            $stmDiruserUser->bindValue("FltDiruserId", $item['diruser']['diruserId']);
                            $stmDiruserUser->execute();
                            $item['diruser']['user'] = $stmDiruserUser->fetch(PDO::FETCH_ASSOC);
                        };
                    // $res[] = $item;
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    // пока, вроде как, не используется
    public function listByUserIdWithCountOrderDate(int $id, int $count, string $ascDesc = 'DESC')
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocsByUserId"(:FltUserId) WHERE "removed" IS NULL ORDER BY "creationDate" '.$ascDesc.' NULLS LAST LIMIT ' . $count);
            $stm->bindValue("FltUserId", $id);
            // $stm->bindValue("FltOrder", $ascDesc);
            // $stm->bindValue("FltCount", $count);
            $stm->execute();
            $res = [];
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                $stmStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1');
                foreach ($result as &$item) {
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                    $stmStatus->bindValue("FltDocumentId", $item["id"]);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    // if ($item['status']['docstatusId'] != 4) {
                    //     $res[] = $item;
                    // }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    // методы с файлами-приложениями AddFiles

    public function addAddFiles(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                $stmFile->bindValue("FltFile", $data->fileLink);
                $stmFile->bindValue("FltFormat", $data->fileFormat);
                $stmFile->bindValue("FltType", 1);
                $stmFile->bindValue("FltComment", null);
                $stmFile->execute();
                $file = $stmFile->fetch(PDO::FETCH_ASSOC);

                if ($file != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $file['id']);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file'] = $file;
                    $result['file']['fileDocId'] = $fileDoc['id'];
                }
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmMail = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltId)');
                    $result['agreementUserId'] = [];
                    $result['emails'] = [];
                    foreach ($data->agreeId as $item) {
                        $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                        $stmUser->bindValue("FltUserId", $item['id']);
                        $stmUser->bindValue("FltOrder", null);
                        $stmUser->execute();
                        $result['agreementUserId'][] = $stmUser->fetch(PDO::FETCH_ASSOC);
                        $stmMail->bindValue("FltId", $item['id']);
                        $stmMail->execute();
                        $result['emails'][] = $stmMail->fetch(PDO::FETCH_ASSOC);
                    }
                }
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 1);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }

                if ($data->addFiles != null) {
                    $stmAddFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                    $stmAdd = $pdo->prepare('SELECT * FROM "AddFileAndAddition"(:FltFileId, :FltDocumentId, :FltAssignmentId, :FltFeedbackId, :FltBlogId, :FltAgreementAndUserId)');
                    $addFiles = [];
                    foreach ($data->addFiles as $item) {
                        $stmAddFile->bindValue("FltFile", $item->fileLink);
                        $stmAddFile->bindValue("FltFormat", $item->format);
                        $stmAddFile->bindValue("FltType", 1);
                        $stmAddFile->bindValue("FltComment", $item->comment);
                        $stmAddFile->execute();
                        $fileAddFile = $stmAddFile->fetch(PDO::FETCH_ASSOC);

                        if (count($fileAddFile) > 0) {
                            $stmAdd->bindValue("FltFileId", $fileAddFile['id']);
                            $stmAdd->bindValue("FltDocumentId", $result['id']);
                            $stmAdd->bindValue("FltAssignmentId", null);
                            $stmAdd->bindValue("FltFeedbackId", null);
                            $stmAdd->bindValue("FltBlogId", null);
                            $stmAdd->bindValue("FltAgreementAndUserId", null);
                            $stmAdd->execute();
                            $addFiles[] = $fileAddFile;
                        }
                    }
                    $result['addFiles'] = $addFiles;
                }
                
                // Рассылка начальству
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as &$item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $item['email'] = $leaders['email'];
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addWithoutAgreersAddFiles(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                $stmFile->bindValue("FltFile", $data->fileLink);
                $stmFile->bindValue("FltFormat", $data->fileFormat);
                $stmFile->bindValue("FltType", 1);
                $stmFile->bindValue("FltComment", null);
                $stmFile->execute();
                $file = $stmFile->fetch(PDO::FETCH_ASSOC);

                if ($file != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $file['id']);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file'] = $file;
                    $result['file']['fileDocId'] = $fileDoc['id'];
                }
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                    $stmUser->bindValue("FltUserId", $data->authorId);
                    $stmUser->bindValue("FltOrder", null);
                    $stmUser->execute();

                    $result['agreementUserId'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                    if ($result['agreementUserId'] != false) {
                        $stmApprove = $pdo->prepare('SELECT * FROM "ApproveAgreementsAndUsersById"(:FltAgreementUserId)');
                        $stmApprove->bindValue("FltAgreementUserId", $result['agreementUserId']['id']);
                        $stmApprove->execute(); //wfeefwefewf
                        $result['agreementApproved'] = $stmApprove->fetch(PDO::FETCH_ASSOC);

                        $stmTotal = $pdo->prepare('SELECT * FROM "ApproveAgreementById"(:FltAgreementId)');
                        $stmTotal->bindValue("FltAgreementId", $result['agreementId']['id']);
                        $stmTotal->execute();
                        $result['agreement'] = $stmTotal->fetch(PDO::FETCH_ASSOC);
                    }
                }

                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 3);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }
                if ($data->addFiles != null) {
                    $stmAddFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                    $stmAdd = $pdo->prepare('SELECT * FROM "AddFileAndAddition"(:FltFileId, :FltDocumentId, :FltAssignmentId, :FltFeedbackId, :FltBlogId, :FltAgreementAndUserId)');
                    $addFiles = [];
                    foreach ($data->addFiles as $item) {
                        $stmAddFile->bindValue("FltFile", $item->fileLink);
                        $stmAddFile->bindValue("FltFormat", $item->format);
                        $stmAddFile->bindValue("FltType", 1);
                        $stmAddFile->bindValue("FltComment", $item->comment);
                        $stmAddFile->execute();
                        $fileAddFile = $stmAddFile->fetch(PDO::FETCH_ASSOC);

                        if (count($fileAddFile) > 0) {
                            $stmAdd->bindValue("FltFileId", $fileAddFile['id']);
                            $stmAdd->bindValue("FltDocumentId", $result['id']);
                            $stmAdd->bindValue("FltAssignmentId", null);
                            $stmAdd->bindValue("FltFeedbackId", null);
                            $stmAdd->bindValue("FltBlogId", null);
                            $stmAdd->bindValue("FltAgreementAndUserId", null);
                            $stmAdd->execute();
                            $addFiles[] = $fileAddFile;
                        }
                    }
                    $result['addFiles'] = $addFiles;
                }

                // Рассылка начальству
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as &$item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $item['email'] = $leaders['email'];
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }


    public function addWithinAgreersOrderAddFiles(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                $stmFile->bindValue("FltFile", $data->fileLink);
                $stmFile->bindValue("FltFormat", $data->fileFormat);
                $stmFile->bindValue("FltType", 1);
                $stmFile->bindValue("FltComment", null);
                $stmFile->execute();
                $file = $stmFile->fetch(PDO::FETCH_ASSOC);

                if ($file != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $file['id']);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file'] = $file;
                    $result['file']['fileDocId'] = $fileDoc['id'];
                }
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmUserNoDate = $pdo->prepare('SELECT * FROM "AddAgreementUserWithoutCreatedAt"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $result['agreementUserId'] = [];
                    $stmMail = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltId)');
                    $result['emails'] = [];
                    for ($i = 0; $i < count($data->agreeId); $i++) {
                        if ($i === 0) {
                            $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                            $stmUser->bindValue("FltUserId", $data->agreeId[$i]['id']);
                            $stmUser->bindValue("FltOrder", $i + 1);
                            $stmUser->execute();
                            $resUser = $stmUser->fetch(PDO::FETCH_ASSOC);
                        } else {
                            $stmUserNoDate->bindValue("FltAgreementId", $result['agreementId']['id']);
                            $stmUserNoDate->bindValue("FltUserId", $data->agreeId[$i]['id']);
                            $stmUserNoDate->bindValue("FltOrder", $i + 1);
                            $stmUserNoDate->execute();
                            $resUser = $stmUserNoDate->fetch(PDO::FETCH_ASSOC);
                        }
                        $result['agreementUserId'][] = $resUser;
                        $stmMail->bindValue("FltId", $data->agreeId[$i]['id']);
                        $stmMail->execute();
                        $result['emails'][] = $stmMail->fetch(PDO::FETCH_ASSOC);
                    }
                }
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 1);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }

                if ($data->addFiles != null) {
                    $stmAddFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                    $stmAdd = $pdo->prepare('SELECT * FROM "AddFileAndAddition"(:FltFileId, :FltDocumentId, :FltAssignmentId, :FltFeedbackId, :FltBlogId, :FltAgreementAndUserId)');
                    $addFiles = [];
                    foreach ($data->addFiles as $item) {
                        $stmAddFile->bindValue("FltFile", $item->fileLink);
                        $stmAddFile->bindValue("FltFormat", $item->format);
                        $stmAddFile->bindValue("FltType", 1);
                        $stmAddFile->bindValue("FltComment", $item->comment);
                        $stmAddFile->execute();
                        $fileAddFile = $stmAddFile->fetch(PDO::FETCH_ASSOC);

                        if (count($fileAddFile) > 0) {
                            $stmAdd->bindValue("FltFileId", $fileAddFile['id']);
                            $stmAdd->bindValue("FltDocumentId", $result['id']);
                            $stmAdd->bindValue("FltAssignmentId", null);
                            $stmAdd->bindValue("FltFeedbackId", null);
                            $stmAdd->bindValue("FltBlogId", null);
                            $stmAdd->bindValue("FltAgreementAndUserId", null);
                            $stmAdd->execute();
                            $addFiles[] = $fileAddFile;
                        }
                    }
                    $result['addFiles'] = $addFiles;
                }
                // Рассылка начальству
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as &$item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $item['email'] = $leaders['email'];
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }


    public function addWithoutAgreersWithoutFileAddFiles(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                if ($data->fileId != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $data->fileId);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file']['fileDocId'] = $fileDoc['id'];
                }
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                    $stmUser->bindValue("FltUserId", $data->authorId);
                    $stmUser->bindValue("FltOrder", null);
                    $stmUser->execute();

                    $result['agreementUserId'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                    if ($result['agreementUserId'] != false) {
                        $stmApprove = $pdo->prepare('SELECT * FROM "ApproveAgreementsAndUsersById"(:FltAgreementUserId)');
                        $stmApprove->bindValue("FltAgreementUserId", $result['agreementUserId']['id']);
                        $stmApprove->execute(); //wfeefwefewf
                        $result['agreementApproved'] = $stmApprove->fetch(PDO::FETCH_ASSOC);

                        $stmTotal = $pdo->prepare('SELECT * FROM "ApproveAgreementById"(:FltAgreementId)');
                        $stmTotal->bindValue("FltAgreementId", $result['agreementId']['id']);
                        $stmTotal->execute();
                        $result['agreement'] = $stmTotal->fetch(PDO::FETCH_ASSOC);
                    }
                }

                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 3);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }

                if ($data->addFiles != null) {
                    $stmAddFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                    $stmAdd = $pdo->prepare('SELECT * FROM "AddFileAndAddition"(:FltFileId, :FltDocumentId, :FltAssignmentId, :FltFeedbackId, :FltBlogId, :FltAgreementAndUserId)');
                    $addFiles = [];
                    foreach ($data->addFiles as $item) {
                        $stmAddFile->bindValue("FltFile", $item->fileLink);
                        $stmAddFile->bindValue("FltFormat", $item->format);
                        $stmAddFile->bindValue("FltType", 1);
                        $stmAddFile->bindValue("FltComment", $item->comment);
                        $stmAddFile->execute();
                        $fileAddFile = $stmAddFile->fetch(PDO::FETCH_ASSOC);

                        if (count($fileAddFile) > 0) {
                            $stmAdd->bindValue("FltFileId", $fileAddFile['id']);
                            $stmAdd->bindValue("FltDocumentId", $result['id']);
                            $stmAdd->bindValue("FltAssignmentId", null);
                            $stmAdd->bindValue("FltFeedbackId", null);
                            $stmAdd->bindValue("FltBlogId", null);
                            $stmAdd->bindValue("FltAgreementAndUserId", null);
                            $stmAdd->execute();
                            $addFiles[] = $fileAddFile;
                        }
                    }
                    $result['addFiles'] = $addFiles;
                }
                // Рассылка начальству
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as &$item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $item['email'] = $leaders['email'];
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addWithinAgreersOrderWithoutFileAddFiles(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                if ($data->fileId != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $data->fileId);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file']['fileDocId'] = $fileDoc['id'];
                }
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmUserNoDate = $pdo->prepare('SELECT * FROM "AddAgreementUserWithoutCreatedAt"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $result['agreementUserId'] = [];
                    $stmMail = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltId)');
                    $result['emails'] = [];
                    for ($i = 0; $i < count($data->agreeId); $i++) {
                        if ($i === 0) {
                            $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                            $stmUser->bindValue("FltUserId", $data->agreeId[$i]['id']);
                            $stmUser->bindValue("FltOrder", $i + 1);
                            $stmUser->execute();
                            $resUser = $stmUser->fetch(PDO::FETCH_ASSOC);
                        } else {
                            $stmUserNoDate->bindValue("FltAgreementId", $result['agreementId']['id']);
                            $stmUserNoDate->bindValue("FltUserId", $data->agreeId[$i]['id']);
                            $stmUserNoDate->bindValue("FltOrder", $i + 1);
                            $stmUserNoDate->execute();
                            $resUser = $stmUserNoDate->fetch(PDO::FETCH_ASSOC);
                        }
                        $result['agreementUserId'][] = $resUser;
                        $stmMail->bindValue("FltId", $data->agreeId[$i]['id']);
                        $stmMail->execute();
                        $result['emails'][] = $stmMail->fetch(PDO::FETCH_ASSOC);
                    }
                }
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 1);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }

                if ($data->addFiles != null) {
                    $stmAddFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                    $stmAdd = $pdo->prepare('SELECT * FROM "AddFileAndAddition"(:FltFileId, :FltDocumentId, :FltAssignmentId, :FltFeedbackId, :FltBlogId, :FltAgreementAndUserId)');
                    $addFiles = [];
                    foreach ($data->addFiles as $item) {
                        $stmAddFile->bindValue("FltFile", $item->fileLink);
                        $stmAddFile->bindValue("FltFormat", $item->format);
                        $stmAddFile->bindValue("FltType", 1);
                        $stmAddFile->bindValue("FltComment", $item->comment);
                        $stmAddFile->execute();
                        $fileAddFile = $stmAddFile->fetch(PDO::FETCH_ASSOC);

                        if (count($fileAddFile) > 0) {
                            $stmAdd->bindValue("FltFileId", $fileAddFile['id']);
                            $stmAdd->bindValue("FltDocumentId", $result['id']);
                            $stmAdd->bindValue("FltAssignmentId", null);
                            $stmAdd->bindValue("FltFeedbackId", null);
                            $stmAdd->bindValue("FltBlogId", null);
                            $stmAdd->bindValue("FltAgreementAndUserId", null);
                            $stmAdd->execute();
                            $addFiles[] = $fileAddFile;
                        }
                    }
                    $result['addFiles'] = $addFiles;
                }
                // Рассылка начальству
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as &$item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $item['email'] = $leaders['email'];
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addWithoutFileAddFiles(DocumentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewDocument"(:FltDesc, :FltAuthorId, :FltFile, :FltDepartmentId, :FltOrderNum, :FltDeliveryId, :FltRecorderId, :FltBaseId, :FltBaseAssignmentId, :FltLinkedDocId, :FltTypeId, :FltName, :FltCreationDate, :FltCloseDate, :FltCoExecutor, :FltColName, :FltSumContract, :FltPhases, :FltNote, :FltAuthor, :FltAcqDate, :FltCustomer, :FltAddresser, :FltExecutor, :FltSignatory, :FltLetterExecutor, :FltOuterNum, :FltOuterDate)');
            $stm->bindValue("FltDesc", $data->description);
            $stm->bindValue("FltAuthorId", $data->authorId);
            $stm->bindValue("FltFile", null);
            $stm->bindValue("FltDepartmentId", $data->departmentId ?? null);
            $stm->bindValue("FltDeliveryId", $data->deliveryId);
            $stm->bindValue("FltOrderNum", $data->orderNum ?? null);
            $stm->bindValue("FltRecorderId", $data->recorderId);
            $stm->bindValue("FltBaseId", $data->baseId ?? null);
            $stm->bindValue("FltBaseAssignmentId", $data->baseAssignmentId ?? null);
            $stm->bindValue("FltLinkedDocId", $data->linkedDocId ?? null);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltName", $data->name ?? null);
            $stm->bindValue("FltCreationDate", $data->creationDate ?? null);
            $stm->bindValue("FltCloseDate", $data->closeDate ?? null);
            $stm->bindValue("FltCoExecutor", $data->coExecutor ?? null);
            $stm->bindValue("FltColName", $data->colName ?? null);
            $stm->bindValue("FltSumContract", $data->sumContract ?? null);
            $stm->bindValue("FltPhases", $data->phases ?? null);
            $stm->bindValue("FltNote", $data->note ?? null);
            $stm->bindValue("FltAuthor", $data->author ?? null);
            $stm->bindValue("FltAcqDate", $data->acqDate ?? null);
            $stm->bindValue("FltCustomer", $data->customer ?? null);
            $stm->bindValue("FltAddresser", $data->addresser ?? null);
            $stm->bindValue("FltExecutor", $data->executor ?? null);
            $stm->bindValue("FltSignatory", $data->signatory ?? null);
            $stm->bindValue("FltLetterExecutor", $data->letterExecutor ?? null);
            $stm->bindValue("FltOuterNum", $data->outerNum ?? null);
            $stm->bindValue("FltOuterDate", $data->outerDate ?? null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                if ($data->fileId != false) {
                    $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                    $stmFileDoc->bindValue("FltDocumentId", $result['id']);
                    $stmFileDoc->bindValue("FltFileId", $data->fileId);
                    $stmFileDoc->execute();
                    $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                    $result['file']['fileDocId'] = $fileDoc['id'];
                };
                $stmAgr = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
                $stmAgr->bindValue("FltDocId", $result['id']);
                $stmAgr->bindValue("FltDeadline", $data->deadline ?? null);
                $stmAgr->execute();
                $result['agreementId'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                if (isset($result['agreementId'])) {
                    $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
                    $stmMail = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltId)');
                    $result['agreementUserId'] = [];
                    $result['emails'] = [];
                    foreach ($data->agreeId as $item) {
                        $stmUser->bindValue("FltAgreementId", $result['agreementId']['id']);
                        $stmUser->bindValue("FltUserId", $item['id']);
                        $stmUser->bindValue("FltOrder", null);
                        $stmUser->execute();
                        $result['agreementUserId'][] = $stmUser->fetch(PDO::FETCH_ASSOC);
                        $stmMail->bindValue("FltId", $item['id']);
                        $stmMail->execute();
                        $result['emails'][] = $stmMail->fetch(PDO::FETCH_ASSOC);
                    }
                }
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 1);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);

                if ($data->diruser != null) {
                    if (isset($data->diruser['id'])) {
                        $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                        $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                        $stmDiruserDoc->bindValue("FltDiruserId", $data->diruser['id']);
                        $stmDiruserDoc->execute();
                        $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmDiruser = $pdo->prepare('SELECT * FROM "AddNewDiruser"(:FltSurname, :FltFirstname, :FltPatronymic, :FltDepartmentId)');
                        $stmDiruser->bindValue("FltSurname", $data->diruser['surname']);
                        $stmDiruser->bindValue("FltFirstname", $data->diruser['firstname']);
                        $stmDiruser->bindValue("FltPatronymic", $data->diruser['patronymic']);
                        $stmDiruser->bindValue("FltDepartmentId", 1);
                        $stmDiruser->execute();
                        $diruser = $stmDiruser->fetch(PDO::FETCH_ASSOC);
                        if (isset($diruser)) {
                            $result['diruser'] = $diruser;
                            $stmDiruserDoc = $pdo->prepare('SELECT * FROM "AddDiruserAndDocument"(:FltDiruserId, :FltDocId)');
                            $stmDiruserDoc->bindValue("FltDocId", $result['id']);
                            $stmDiruserDoc->bindValue("FltDiruserId", $diruser['id']);
                            $stmDiruserDoc->execute();
                            $result['diruser'] = $stmDiruserDoc->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                }

                if ($data->addFiles != null) {
                    $stmAddFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
                    $stmAdd = $pdo->prepare('SELECT * FROM "AddFileAndAddition"(:FltFileId, :FltDocumentId, :FltAssignmentId, :FltFeedbackId, :FltBlogId, :FltAgreementAndUserId)');
                    $addFiles = [];
                    foreach ($data->addFiles as $item) {
                        $stmAddFile->bindValue("FltFile", $item->fileLink);
                        $stmAddFile->bindValue("FltFormat", $item->format);
                        $stmAddFile->bindValue("FltType", 1);
                        $stmAddFile->bindValue("FltComment", $item->comment);
                        $stmAddFile->execute();
                        $fileAddFile = $stmAddFile->fetch(PDO::FETCH_ASSOC);

                        if (count($fileAddFile) > 0) {
                            $stmAdd->bindValue("FltFileId", $fileAddFile['id']);
                            $stmAdd->bindValue("FltDocumentId", $result['id']);
                            $stmAdd->bindValue("FltAssignmentId", null);
                            $stmAdd->bindValue("FltFeedbackId", null);
                            $stmAdd->bindValue("FltBlogId", null);
                            $stmAdd->bindValue("FltAgreementAndUserId", null);
                            $stmAdd->execute();
                            $addFiles[] = $fileAddFile;
                        }
                    }
                    $result['addFiles'] = $addFiles;
                }
                // Рассылка начальству
                if (($data->typeId == 6) || ($data->typeId == 7)) {
                    $stmLeaders = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
                    $stmLeaders->bindValue("FltRoleId", 4);
                    $stmLeaders->execute();
                    $leaders = $stmLeaders->fetchAll(PDO::FETCH_ASSOC);
                    $stmAcq = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
                    $result['acquaintances'] = [];
                    if ($leaders != []) {
                        foreach ($leaders as &$item) {
                            $stmAcq->bindValue("FltDocumentId", $result['id']);
                            $stmAcq->bindValue("FltUserId", $item['id']);
                            $stmAcq->bindValue("FltInitiatorId", $data->authorId);
                            $stmAcq->execute();
                            $item['email'] = $leaders['email'];
                            $result['acquaintances'][] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }
}
