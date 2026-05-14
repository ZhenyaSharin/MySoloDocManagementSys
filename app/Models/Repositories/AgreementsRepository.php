<?php

namespace App\Models\Repositories;

use App\Dto\AgreementDto\AgreementDto;
use App\Dto\AgreementDto\AgreementUserDto;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\AgreementsRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use PDO;

class AgreementsRepository implements AgreementsRepositoryInterface
{

    public function add(int $userId, int $documentId, string $deadline)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewAgreement"(:FltDocId, :FltDeadline)');
            $stm->bindValue("FltDocId", $documentId);
            $stm->bindValue("FltDeadline", $deadline);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != null) {
                $stmUser = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId)');
                $stmUser->bindValue("FltAgreementId", $result['id']);
                $stmUser->bindValue("FltUserId", $userId);
                $stmUser->execute();
                $result['agreementId'] = $stmUser->fetch(PDO::FETCH_ASSOC);
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addAgreementUser(AgreementUserDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId)');
            $stm->bindValue("FltAgreementId", $data->agreementId);
            $stm->bindValue("FltUserId", $data->userId);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listByUserId(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByUserId"(:FltUserId) WHERE "removed" IS NULL AND "created_at" IS NOT NULL ORDER BY "created_at" DESC');
            $stm->bindValue("FltUserId", $id);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function listNonViewedByUserId(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT "id", "agreementId", "order" FROM "GetAgreementAndUserByUserId"(:FltUserId) WHERE "removed" IS NULL AND "refused_at" IS NULL AND "approved_at" IS NULL AND "viewed_at" IS NULL ORDER BY "created_at" DESC');
            $stm->bindValue("FltUserId", $id);
            $stm->execute();
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            if (isset($res)) {
                $stmPrev = $pdo->prepare('SELECT "id" FROM "GetAgreementAndUserByAgreementId"(:FltAgreementId) WHERE "order" = :FltOrder AND ("approved_at" IS NOT NULL OR "refused_at" IS NOT NULL)');
                $stmAgr = $pdo->prepare('SELECT "id", "documentId" FROM "GetAgreementById"(:FltAgreementId)');
                $stmDoc = $pdo->prepare('SELECT "id", "description" FROM "GetDocumentById"(:FltDocumentId) WHERE "removed" IS NULL');
                foreach ($res as $item) {
                    $stmAgr->bindValue("FltAgreementId", $item['agreementId']);
                    $stmAgr->execute();
                    $agr = $stmAgr->fetch(PDO::FETCH_ASSOC);

                    $stmDoc->bindValue("FltDocumentId", $agr['documentId']);
                    $stmDoc->execute();
                    $doc = $stmDoc->fetch(PDO::FETCH_ASSOC);
                    if ($doc) {
                        if ($item['order'] != null) {
                            if ($item['order'] == 1) {
                                $result[] = $item;
                            } else {
                                $stmPrev->bindValue("FltAgreementId", $item['agreementId']);
                                $stmPrev->bindValue("FltOrder", $item['order'] - 1);
                                $stmPrev->execute();
                                $order = $stmPrev->fetch(PDO::FETCH_ASSOC);
                                if ($order != false) {
                                    $result[] = $item;
                                }
                            }
                        } else {
                            $result[] = $item;
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function agreementsByDocId(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAgreementByDocumentId"(:FltDocId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
            $stm->bindValue("FltDocId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $result['users'] = [];
                $stmUser = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByAgreementId"(:FltAgreementId) WHERE "removed" IS NULL ORDER BY "id" ASC');
                $stmUser->bindValue("FltAgreementId", $result['id']);
                $stmUser->execute();
                $result['users'] = $stmUser->fetchAll(PDO::FETCH_ASSOC);
                if ($result['users'] != []) {
                    $stmUserInfo = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                    foreach ($result['users'] as &$item) {
                        $stmUserInfo->bindValue('FltUserId', $item['userId']);
                        $stmUserInfo->execute();
                        $item['user'] = $stmUserInfo->fetch(PDO::FETCH_ASSOC);
                    }
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
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function agreementsByDocIdWithInfo(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAgreementByDocumentId"(:FltDocId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
            $stm->bindValue("FltDocId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $result['users'] = [];
                $stmUser = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByAgreementId"(:FltAgreementId) ORDER BY "id" ASC');
                $stmUser->bindValue("FltAgreementId", $result['id']);
                $stmUser->execute();
                $result['users'] = $stmUser->fetchAll(PDO::FETCH_ASSOC);
                if ($result['users'] != []) {
                    $stmUserInfo = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                    foreach ($result['users'] as &$item) {
                        $stmUserInfo->bindValue('FltUserId', $item['userId']);
                        $stmUserInfo->execute();
                        $item['user'] = $stmUserInfo->fetch(PDO::FETCH_ASSOC);
                    }
                }
                $stmDoc = $pdo->prepare('SELECT "description", "file", "typeId", "authorId", "created_at" FROM "GetDocumentById"(:FltDocId)');
                $stmDoc->bindValue("FltDocId", $id);
                $stmDoc->execute();
                $result['document'] = $stmDoc->fetch(PDO::FETCH_ASSOC);
                if (isset($result['document']['typeId'])) {
                    $stmDocType = $pdo->prepare('SELECT "title" FROM "GetDocTypeById"(:FltTypeId)');
                    $stmDocType->bindValue("FltTypeId", $result['document']['typeId']);
                    $stmDocType->execute();
                    $result['documentType'] = $stmDocType->fetch(PDO::FETCH_ASSOC);
                }
                if (isset($result['document']['authorId'])) {
                    $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                    $stmAuthor->bindValue("FltAuthorId", $result['document']['authorId']);
                    $stmAuthor->execute();
                    $result['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                }

                $stmDocFile = $pdo->prepare('SELECT * FROM "GetDocumentAndFileByDocumentId"(:FltDocumentId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDocFile->bindValue("FltDocumentId", $result['documentId']);
                $stmDocFile->execute();
                $docFile = $stmDocFile->fetch(PDO::FETCH_ASSOC);
                if ($docFile != false) {
                    $stmFile = $pdo->prepare('SELECT * FROM "GetFileById"(:FltId)');
                    $stmFile->bindValue("FltId", $docFile['fileId']);
                    $stmFile->execute();
                    $result['file'] = $stmFile->fetch(PDO::FETCH_ASSOC);
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function refuseAgreement(AgreementUserDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RefuseAgreement"(:FltAgreementId)');
            $stmUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic", "email" FROM "GetUserById"(:FltUserId)');
            $stm->bindValue("FltAgreementId", $data->agreementId);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $result['users'] = [];
            if (isset($result)) {
                $stmAgreeUser = $pdo->prepare('SELECT * FROM "RefuseAgreementsAndUsersById"(:FltId)');
                $stmAgreeUser->bindValue("FltId", $data->id);
                $stmAgreeUser->execute();
                $result['users'] = $stmAgreeUser->fetchAll(PDO::FETCH_ASSOC);
                $stmUser->bindValue("FltUserId", $data->authorId);
                $stmUser->execute();
                $result['author'] = $stmUser->fetch(PDO::FETCH_ASSOC);

                $stmUser->bindValue("FltUserId", $data->userId);
                $stmUser->execute();
                $result['user'] = $stmUser->fetch(PDO::FETCH_ASSOC);
            }
            $result['note'] = [];
            if ($data->note != null) {
                $stmNote = $pdo->prepare('SELECT * FROM "UpdateAgreementUserWithNote"(:FltId, :FltNote)');
                $stmNote->bindValue("FltId", $data->id);
                $stmNote->bindValue("FltNote", $data->note);
                $stmNote->execute();
                $result['note'] = $stmNote->fetch(PDO::FETCH_ASSOC);
            }
            $stmCheck = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByAgreementId"(:FltAgreementId) ORDER BY "id" ASC');
            $stmCheck->bindValue("FltAgreementId", $data->agreementId);
            $stmCheck->execute();
            $checks = $stmCheck->fetchAll(PDO::FETCH_ASSOC);
            if ($checks != []) {
                foreach ($checks as &$item) {
                    $stmUser->bindValue("FltUserId", $item['userId']);
                    $stmUser->execute();
                    $item['user'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                }
                $result['agreeList'] = $checks;
            }
            $result["status"] = [];
            $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatusByAgreementId"(:FltAgreementId, :FltStatusId)');
            $stmStatus->bindValue("FltAgreementId", $data->agreementId);
            $stmStatus->bindValue("FltStatusId", 2);
            $stmStatus->execute();
            $result["status"] = $stmStatus->fetch(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function approveAgreement(AgreementUserDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "ApproveAgreementsAndUsersById"(:FltId)');
            $stm->bindValue("FltId", $data->id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $result = [];
            $result['note'] = [];
            if ($data->note != null) {
                $stmNote = $pdo->prepare('SELECT * FROM "UpdateAgreementUserWithNote"(:FltId, :FltNote)');
                $stmNote->bindValue("FltId", $data->id);
                $stmNote->bindValue("FltNote", $data->note);
                $stmNote->execute();
                $result['note'] = $stmNote->fetch(PDO::FETCH_ASSOC);
            }
            $stmCheck = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByAgreementId"(:FltAgreementId) ORDER BY "id" ASC');
            $stmUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic", "email" FROM "GetUserById"(:FltUserId)');
            $stmCheck->bindValue("FltAgreementId", $data->agreementId);
            $stmCheck->execute();
            $checks = $stmCheck->fetchAll(PDO::FETCH_ASSOC);
            $checker = 1;
            foreach ($checks as &$item) {
                if ($item['approved_at'] === null) {
                    $checker = 0;
                }
                $stmUser->bindValue("FltUserId", $item['userId']);
                $stmUser->execute();
                $item['user'] = $stmUser->fetch(PDO::FETCH_ASSOC);
            }
            $result['agreeList'] = $checks;
            $stmUser->bindValue("FltUserId", $data->authorId);
            $stmUser->execute();
            $result['author'] = $stmUser->fetch(PDO::FETCH_ASSOC);
            if ($checker === 1) {
                $result['agreement'] = [];
                $stmTotal = $pdo->prepare('SELECT * FROM "ApproveAgreementById"(:FltAgreementId)');
                $stmTotal->bindValue("FltAgreementId", $data->agreementId);
                $stmTotal->execute();
                $result['agreement'] = $stmTotal->fetch(PDO::FETCH_ASSOC);
                $result["status"] = [];
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatusByAgreementId"(:FltAgreementId, :FltStatusId)');
                $stmStatus->bindValue("FltAgreementId", $data->agreementId);
                $stmStatus->bindValue("FltStatusId", 3);
                $stmStatus->execute();
                $result["status"] = $stmStatus->fetch(PDO::FETCH_ASSOC);
            } elseif ($data->order != null) {
                $stmNext = $pdo->prepare('SELECT * FROM  "UpdateAgreementUserWithCreatedAt"(:FltAgreementId, :FltOrder)');
                $stmNext->bindValue("FltAgreementId", $data->agreementId);
                $stmNext->bindValue("FltOrder", $data->order + 1);
                $stmNext->execute();
                $next = $stmNext->fetch(PDO::FETCH_ASSOC);
                if ($next != false) {
                    $result['next'] = $next;
                    // $stmNextUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic", "email" FROM "GetUserById"(:FltUserId)');
                    $stmUser->bindValue("FltUserId", $result['next']['userId']);
                    $stmUser->execute();
                    $result['next']['user'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function agrResponsesByUserIdWithLimit(int $id, int $count)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocsByUserId"(:FltUserId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT :FltCount');
            $stm->bindValue("FltUserId", $id);
            $stm->bindValue("FltCount", $count);
            $stm->execute();
            $docs = $stm->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            if ($docs != null) {
                $stmAgr = $pdo->prepare('SELECT * FROM "GetAgreementByDocumentId"(:FltDocumentId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDocType = $pdo->prepare('SELECT * FROM "GetDocumentTypeById"(:FltDocTypeId)');
                $stmAU = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByAgreementId"(:FltId) ORDER BY "created_at"');
                $stmUser = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic", "department" FROM "GetUserById"(:FltUserId) WHERE "removed" IS NULL');
                $res = [];
                foreach ($docs as &$item) {
                    if ($item != null) {
                        $stmAgr->bindValue("FltDocumentId", $item['id']);
                        $stmAgr->execute();
                        $agr = $stmAgr->fetch(PDO::FETCH_ASSOC);
                        if ($agr != null) {
                            $agr['document'] = $item;
                            $stmDocType->bindValue("FltDocTypeId", $item['typeId']);
                            $stmDocType->execute();
                            $agr['document']['documentType'] = $stmDocType->fetch(PDO::FETCH_ASSOC)['title'];
                            $res[] = $agr;
                        }
                    }
                }
                foreach ($res as &$item) {
                    $stmAU->bindValue("FltId", $item['id']);
                    $stmAU->execute();
                    // $item['agreementsAndUsers'] = $stmAU->fetchAll(PDO::FETCH_ASSOC);
                    $agrUser = $stmAU->fetchAll(PDO::FETCH_ASSOC);
                    if (($agrUser[0]['userId'] != $id) && (count($agrUser) > 1)) {
                        foreach ($agrUser as &$value) {
                            if (isset($value['userId'])) {
                                $stmUser->bindValue("FltUserId", $value['userId']);
                                $stmUser->execute();
                                $value['user'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                            }
                        }
                        $item['agreementsAndUsers'] = $agrUser;
                        $result[] = $item;
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function agrResponsesByUserId(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetDocsByUserId"(:FltUserId) WHERE "removed" IS NULL ORDER BY "created_at" DESC');
            $stm->bindValue("FltUserId", $id);
            $stm->execute();
            $docs = $stm->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            if ($docs != null) {
                $stmAgr = $pdo->prepare('SELECT * FROM "GetAgreementByDocumentId"(:FltDocumentId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmDocType = $pdo->prepare('SELECT * FROM "GetDocumentTypeById"(:FltDocTypeId)');
                $stmAU = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByAgreementId"(:FltId) ORDER BY "created_at"');
                $stmUser = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic", "department" FROM "GetUserById"(:FltUserId) WHERE "removed" IS NULL');
                $res = [];
                foreach ($docs as &$item) {
                    if ($item != null) {
                        $stmAgr->bindValue("FltDocumentId", $item['id']);
                        $stmAgr->execute();
                        $agr = $stmAgr->fetch(PDO::FETCH_ASSOC);
                        if ($agr != null) {
                            $agr['document'] = $item;
                            $stmDocType->bindValue("FltDocTypeId", $item['typeId']);
                            $stmDocType->execute();
                            $agr['document']['documentType'] = $stmDocType->fetch(PDO::FETCH_ASSOC)['title'];
                            $res[] = $agr;
                        }
                    }
                }
                foreach ($res as &$item) {
                    $stmAU->bindValue("FltId", $item['id']);
                    $stmAU->execute();
                    // $item['agreementsAndUsers'] = $stmAU->fetchAll(PDO::FETCH_ASSOC);
                    $agrUser = $stmAU->fetchAll(PDO::FETCH_ASSOC);
                    if (($agrUser[0]['userId'] != $id) && (count($agrUser) > 1)) {
                        foreach ($agrUser as &$value) {
                            if (isset($value['userId'])) {
                                $stmUser->bindValue("FltUserId", $value['userId']);
                                $stmUser->execute();
                                $value['user'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                            }
                        }
                        $item['agreementsAndUsers'] = $agrUser;
                        $result[] = $item;
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function agrListByUserId(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByUserId"(:FltUserId) WHERE "removed" IS NULL AND "refused_at" IS NULL AND "approved_at" IS NULL ORDER BY "created_at" DESC');
            $stm->bindValue("FltUserId", $id);
            $stm->execute();
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            if (isset($res)) {
                $stm2 = $pdo->prepare('SELECT * FROM "GetAgreementById"(:FltId) WHERE "removed" IS NULL');
                $stmDoc = $pdo->prepare('SELECT "description", "authorId", "typeId", "file", "created_at" FROM "GetDocumentById"(:FltDocumentId) WHERE "removed" IS NULL');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                $stmDocType = $pdo->prepare('SELECT "title" FROM "GetDocumentTypeById"(:FltDocTypeId)');
                $stmDocStatus = $pdo->prepare('SELECT "id", "docstatusId", "docStatusTitle" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmOrder = $pdo->prepare('SELECT * FROM "GetAgreementAndUserInOrder"(:FltUserId, :FltAgreementId)');
                foreach ($res as &$item) {
                    if ($item['order'] == null) {
                        $stm2->bindValue("FltId", $item["agreementId"]);
                        $stm2->execute();
                        $agr = $stm2->fetch(PDO::FETCH_ASSOC);
                        if ($agr != false) {
                            $stmDoc->bindValue("FltDocumentId", $agr['documentId']);
                            $stmDoc->execute();
                            $doc = $stmDoc->fetch(PDO::FETCH_ASSOC);
                            if ($doc != false) {
                                $stmAuthor->bindValue("FltAuthorId", $doc["authorId"]);
                                $stmAuthor->execute();
                                $author = $stmAuthor->fetch(PDO::FETCH_ASSOC);

                                $stmDocType->bindValue("FltDocTypeId", $doc["typeId"]);
                                $stmDocType->execute();
                                $docType = $stmDocType->fetch(PDO::FETCH_ASSOC);
                                $item['documentType'] = $docType['title'];

                                $stmDocStatus->bindValue("FltDocumentId", $agr['documentId']);
                                $stmDocStatus->execute();
                                $item['status'] = $stmDocStatus->fetch(PDO::FETCH_ASSOC);
                                if ($item['status']['docstatusId'] == 1) {
                                    $item['documentDescription'] = $doc["description"];
                                    $item['documentFile'] = $doc["file"];
                                    $item['agreement'] = $agr;
                                    $item['author'] = $author;
                                    $result[] = $item;
                                }
                            }
                        }
                    } else {
                        $stmOrder->bindValue("FltAgreementId", $item["agreementId"]);
                        $stmOrder->bindValue("FltUserId", $id);
                        $stmOrder->execute();
                        $ordr = $stmOrder->fetch(PDO::FETCH_ASSOC);
                        if ($ordr != null) {
                            $stm2->bindValue("FltId", $ordr["agreementId"]);
                            $stm2->execute();
                            $agr = $stm2->fetch(PDO::FETCH_ASSOC);
                            if ($agr != false) {
                                $stmDoc->bindValue("FltDocumentId", $agr['documentId']);
                                $stmDoc->execute();
                                $doc = $stmDoc->fetch(PDO::FETCH_ASSOC);
                                if ($doc != false) {
                                    $stmAuthor->bindValue("FltAuthorId", $doc["authorId"]);
                                    $stmAuthor->execute();
                                    $author = $stmAuthor->fetch(PDO::FETCH_ASSOC);

                                    $stmDocType->bindValue("FltDocTypeId", $doc["typeId"]);
                                    $stmDocType->execute();
                                    $docType = $stmDocType->fetch(PDO::FETCH_ASSOC);
                                    $item['documentType'] = $docType['title'];

                                    $stmDocStatus->bindValue("FltDocumentId", $agr['documentId']);
                                    $stmDocStatus->execute();
                                    $item['status'] = $stmDocStatus->fetch(PDO::FETCH_ASSOC);
                                    if ($item['status']['docstatusId'] == 1) {
                                        $item['documentDescription'] = $doc["description"];
                                        $item['documentFile'] = $doc["file"];
                                        $item['agreement'] = $agr;
                                        $item['author'] = $author;
                                        $result[] = $item;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // echo '<pre>';
            // print_r($result);
            // echo '</pre>';
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function agrListByUserIdWithCount(int $id, int $count)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByUserId"(:FltUserId) WHERE "removed" IS NULL AND "refused_at" IS NULL AND "approved_at" IS NULL ORDER BY "created_at" DESC LIMIT :FltCount');
            $stm->bindValue("FltUserId", $id);
            $stm->bindValue("FltCount", $count);
            $stm->execute();
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            if (isset($res)) {
                $stm2 = $pdo->prepare('SELECT * FROM "GetAgreementById"(:FltId) WHERE "removed" IS NULL');
                $stmDoc = $pdo->prepare('SELECT "description", "authorId", "typeId", "file", "created_at" FROM "GetDocumentById"(:FltDocumentId) WHERE "removed" IS NULL');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                $stmDocType = $pdo->prepare('SELECT "title" FROM "GetDocumentTypeById"(:FltDocTypeId)');
                $stmDocStatus = $pdo->prepare('SELECT "id", "docstatusId", "docStatusTitle" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmOrder = $pdo->prepare('SELECT * FROM "GetAgreementAndUserInOrder"(:FltUserId, :FltAgreementId)');
                foreach ($res as &$item) {
                    if ($item['order'] == null) {
                        $stm2->bindValue("FltId", $item["agreementId"]);
                        $stm2->execute();
                        $agr = $stm2->fetch(PDO::FETCH_ASSOC);
                        if ($agr != false) {
                            $stmDoc->bindValue("FltDocumentId", $agr['documentId']);
                            $stmDoc->execute();
                            $doc = $stmDoc->fetch(PDO::FETCH_ASSOC);

                            if ($doc != false) {
                                $stmAuthor->bindValue("FltAuthorId", $doc["authorId"]);
                                $stmAuthor->execute();
                                $author = $stmAuthor->fetch(PDO::FETCH_ASSOC);

                                $stmDocType->bindValue("FltDocTypeId", $doc["typeId"]);
                                $stmDocType->execute();
                                $docType = $stmDocType->fetch(PDO::FETCH_ASSOC);
                                $item['documentType'] = $docType['title'];

                                $stmDocStatus->bindValue("FltDocumentId", $agr['documentId']);
                                $stmDocStatus->execute();
                                $item['status'] = $stmDocStatus->fetch(PDO::FETCH_ASSOC);

                                if ($item['status']['docstatusId'] == 1) {
                                    $item['documentDescription'] = $doc["description"];
                                    $item['documentFile'] = $doc["file"];
                                    $item['agreement'] = $agr;
                                    $item['author'] = $author;
                                    $result[] = $item;
                                }
                            }
                        }
                    } else {
                        $stmOrder->bindValue("FltAgreementId", $item["agreementId"]);
                        $stmOrder->bindValue("FltUserId", $id);
                        $stmOrder->execute();
                        $ordr = $stmOrder->fetch(PDO::FETCH_ASSOC);
                        if ($ordr != null) {
                            $stm2->bindValue("FltId", $ordr["agreementId"]);
                            $stm2->execute();
                            $agr = $stm2->fetch(PDO::FETCH_ASSOC);
                            if ($agr != null) {
                                $stmDoc->bindValue("FltDocumentId", $agr['documentId']);
                                $stmDoc->execute();
                                $doc = $stmDoc->fetch(PDO::FETCH_ASSOC);

                                $stmAuthor->bindValue("FltAuthorId", $doc["authorId"]);
                                $stmAuthor->execute();
                                $author = $stmAuthor->fetch(PDO::FETCH_ASSOC);

                                $stmDocType->bindValue("FltDocTypeId", $doc["typeId"]);
                                $stmDocType->execute();
                                $docType = $stmDocType->fetch(PDO::FETCH_ASSOC);
                                $item['documentType'] = $docType['title'];

                                $stmDocStatus->bindValue("FltDocumentId", $agr['documentId']);
                                $stmDocStatus->execute();
                                $item['status'] = $stmDocStatus->fetch(PDO::FETCH_ASSOC);
                                if ($item['status']['docstatusId'] == 1) {
                                    $item['documentDescription'] = $doc["description"];
                                    $item['documentFile'] = $doc["file"];
                                    $item['agreement'] = $agr;
                                    $item['author'] = $author;
                                    $result[] = $item;
                                }
                            }
                        }
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addAgreement(AgreementUserDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId)');
            $stm->bindValue("FltAgreementId", $data->agreementId);
            $stm->bindValue("FltUserId", $data->userId);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if (isset($result)) {
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocId, :FltStatusId)');
                $stmStatus->bindValue("FltDocId", $result['id']);
                $stmStatus->bindValue("FltStatusId", 1);
                $stmStatus->execute();
                $result['docStatus'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function agreementsAndUsersHistory(AgreementUserDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByUserId"(:FltUserId) WHERE "removed" IS NULL AND ("approved_at" IS NOT NULL OR "refused_at" IS NOT NULL) ORDER BY "updated_at" DESC');
            $stm->bindValue("FltUserId", $data->userId);
            $stm->execute();
            $result = [];
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            if ($res != null) {
                $stmAgrUser = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByAgreementId"(:FltAgreementId) ORDER BY "id" ASC');
                $stmAgr = $pdo->prepare('SELECT * FROM "GetAgreementById"(:FltAgreementId)');
                $stmDoc = $pdo->prepare('SELECT "description", "authorId", "typeId", "created_at" FROM "GetDocumentById"(:FltDocumentId)');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                $stmDocType = $pdo->prepare('SELECT "id", "title" FROM "GetDocumentTypeById"(:FltDocTypeId)');
                $stmDocStatus = $pdo->prepare('SELECT "id", "docstatusId", "docStatusTitle" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) ORDER BY "created_at" DESC LIMIT 1');
                foreach ($res as &$item) {
                    $stmAgrUser->bindValue("FltAgreementId", $item['agreementId']);
                    $stmAgrUser->execute();
                    $agrUserArr = $stmAgrUser->fetchAll(PDO::FETCH_ASSOC);
                    if (($agrUserArr[0]['userId'] != $data->userId) && (count($agrUserArr) > 1)) {
                        $stmAgr->bindValue("FltAgreementId", $item['agreementId']);
                        $stmAgr->execute();
                        $item['agreement'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                        if ($item['agreement'] != false) {
                            $stmDoc->bindValue("FltDocumentId", $item['agreement']['documentId']);
                            $stmDoc->execute();
                            // echo '1,';
                            $item['document'] = $stmDoc->fetch(PDO::FETCH_ASSOC);
                            $stmAuthor->bindValue("FltAuthorId", $item['document']['authorId']);
                            $stmAuthor->execute();
                            $item['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                            $stmDocType->bindValue("FltDocTypeId", $item['document']['typeId']);
                            $stmDocType->execute();
                            $item['documentType'] = $stmDocType->fetch(PDO::FETCH_ASSOC);
                            $stmDocStatus->bindValue("FltDocumentId", $item['agreement']['documentId']);
                            $stmDocStatus->execute();
                            // echo 'wd';
                            $item['documentStatus'] = $stmDocStatus->fetch(PDO::FETCH_ASSOC);
                        }
                        $result[] = $item;
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function agreementsAndUsersHistoryWithCount(AgreementUserDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByUserId"(:FltUserId) WHERE "removed" IS NULL AND ("approved_at" IS NOT NULL OR "refused_at" IS NOT NULL) ORDER BY "updated_at" DESC LIMIT :FltCount');
            $stm->bindValue("FltUserId", $data->userId);
            $stm->bindValue("FltCount", $data->count);
            $stm->execute();
            $result = [];
            $res = $stm->fetchAll(PDO::FETCH_ASSOC);
            if ($res != null) {
                $stmAgrUser = $pdo->prepare('SELECT * FROM "GetAgreementAndUserByAgreementId"(:FltAgreementId) ORDER BY "id" ASC');
                $stmAgr = $pdo->prepare('SELECT * FROM "GetAgreementById"(:FltAgreementId)');
                $stmDoc = $pdo->prepare('SELECT "description", "authorId", "typeId", "created_at" FROM "GetDocumentById"(:FltDocumentId)');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                $stmDocType = $pdo->prepare('SELECT "id", "title" FROM "GetDocumentTypeById"(:FltDocTypeId)');
                $stmDocStatus = $pdo->prepare('SELECT "id", "docstatusId", "docStatusTitle" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) ORDER BY "created_at" DESC LIMIT 1');
                foreach ($res as &$item) {
                    $stmAgrUser->bindValue("FltAgreementId", $item['agreementId']);
                    $stmAgrUser->execute();
                    $agrUserArr = $stmAgrUser->fetchAll(PDO::FETCH_ASSOC);
                    if (($agrUserArr[0]['userId'] != $data->userId) && (count($agrUserArr) > 1)) {
                        $stmAgr->bindValue("FltAgreementId", $item['agreementId']);
                        $stmAgr->execute();
                        $item['agreement'] = $stmAgr->fetch(PDO::FETCH_ASSOC);
                        if ($item['agreement'] != false) {
                            $stmDoc->bindValue("FltDocumentId", $item['agreement']['documentId']);
                            $stmDoc->execute();
                            // echo '1,';
                            $item['document'] = $stmDoc->fetch(PDO::FETCH_ASSOC);
                            $stmAuthor->bindValue("FltAuthorId", $item['document']['authorId']);
                            $stmAuthor->execute();
                            $item['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                            $stmDocType->bindValue("FltDocTypeId", $item['document']['typeId']);
                            $stmDocType->execute();
                            $item['documentType'] = $stmDocType->fetch(PDO::FETCH_ASSOC);
                            $stmDocStatus->bindValue("FltDocumentId", $item['agreement']['documentId']);
                            $stmDocStatus->execute();
                            // echo 'wd';
                            $item['documentStatus'] = $stmDocStatus->fetch(PDO::FETCH_ASSOC);
                        }
                        $result[] = $item;
                    }
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function makeViewedAgreementUser(AgreementUserDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "ViewAgreementAndUserById"(:FltId)');
            $stm->bindValue("FltId", $data->id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function removeAgreement(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RemoveAgreement"(:FltId)');
            $stm->bindValue("FltId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addAgreementAndUser(AgreementDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
            $stmUser = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltUserId)');
            $result = [];
            foreach ($data->users as $item) {
                $stm->bindValue("FltAgreementId", $data->id);
                $stm->bindValue("FltUserId", $item->userId);
                $stm->bindValue("FltOrder", null);
                $stm->execute();
                $res = $stm->fetch(PDO::FETCH_ASSOC);

                $stmUser->bindValue("FltUserId", $item->userId);
                $stmUser->execute();
                $res['email'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                $result[] = $res;
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addAgreementAndUserWithOrder(AgreementDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddAgreementUserWithoutCreatedAt"(:FltAgreementId, :FltUserId, :FltOrder)');
            $result = [];
            foreach ($data->users as $item) {
                $stm->bindValue("FltAgreementId", $data->id);
                $stm->bindValue("FltUserId", $item->userId);
                $stm->bindValue("FltOrder", $item->order);
                $stm->execute();
                $result[] = $stm->fetch(PDO::FETCH_ASSOC);
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addAgreementAndUserWithStatus(AgreementDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
            $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocumentId, :FltStatusId)');
            $stmUser = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltUserId)');
            $stmStatus->bindValue('FltDocumentId', $data->documentId);
            $stmStatus->bindValue('FltStatusId', 1);
            $stmStatus->execute();
            $status = $stmStatus->fetch(PDO::FETCH_ASSOC);
            $result = [];
            if ($status != false) {
                foreach ($data->users as $item) {
                    $stm->bindValue("FltAgreementId", $data->id);
                    $stm->bindValue("FltUserId", $item->userId);
                    $stm->bindValue("FltOrder", null);
                    $stm->execute();
                    $res = $stm->fetch(PDO::FETCH_ASSOC);

                    $stmUser->bindValue("FltUserId", $item->userId);
                    $stmUser->execute();
                    $res['email'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                    $result[] = $res;
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addAgreementAndUserCompleted(AgreementDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
            $stmWithout = $pdo->prepare('SELECT * FROM "AddAgreementUserWithoutCreatedAt"(:FltAgreementId, :FltUserId, :FltOrder)');
            $result = [];
            for ($i = 0; $i < count($data->users); $i++) {
                if ($data->users[0]) {
                    $stm->bindValue("FltAgreementId", $data->id);
                    $stm->bindValue("FltUserId", $data->users[0]->userId);
                    $stm->bindValue("FltOrder", $data->users[0]->order);
                    $stm->execute();
                    $result[] = $stm->fetch(PDO::FETCH_ASSOC);
                } else {
                    $stmWithout->bindValue("FltAgreementId", $data->id);
                    $stmWithout->bindValue("FltUserId", $data->users[$i]->userId);
                    $stmWithout->bindValue("FltOrder", $data->users[$i]->order);
                    $stmWithout->execute();
                    $result[] = $stmWithout->fetch(PDO::FETCH_ASSOC);
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function addAgreementAndUserWithStatusAndOrderCompleted(AgreementDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddAgreementUser"(:FltAgreementId, :FltUserId, :FltOrder)');
            $stmWithout = $pdo->prepare('SELECT * FROM "AddAgreementUserWithoutCreatedAt"(:FltAgreementId, :FltUserId, :FltOrder)');
            $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocumentId, :FltStatusId)');
            $stmUser = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltUserId)');
            $stmStatus->bindValue('FltDocumentId', $data->documentId);
            $stmStatus->bindValue('FltStatusId', 1);
            $stmStatus->execute();
            $status = $stmStatus->fetch(PDO::FETCH_ASSOC);
            $result = [];
            if ($status != false) {
                for ($i = 0; $i < count($data->users); $i++) {
                    if ($i === 0) {
                        $stm->bindValue("FltAgreementId", $data->id);
                        $stm->bindValue("FltUserId", $data->users[0]->userId);
                        $stm->bindValue("FltOrder", $data->users[0]->order);
                        $stm->execute();
                        $res = $stm->fetch(PDO::FETCH_ASSOC);
                        $stmUser->bindValue('FltUserId', $data->users[0]->userId);
                        $stmUser->execute();
                        $res['email'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $stmWithout->bindValue("FltAgreementId", $data->id);
                        $stmWithout->bindValue("FltUserId", $data->users[$i]->userId);
                        $stmWithout->bindValue("FltOrder", $data->users[$i]->order);
                        $stmWithout->execute();
                        $res = $stmWithout->fetch(PDO::FETCH_ASSOC);
                    }
                    $result[] = $res;
                }
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function removeAgreementUser(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RemoveAgreementAndUser"(:FltId)');
            $stm->bindValue("FltId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function removeAgreementUserWithComplete(int $id, int $documentId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RemoveAgreementAndUser"(:FltId)');
            $stm->bindValue("FltId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                $stmStatus = $pdo->prepare('SELECT * FROM "AddNewDocumentStatus"(:FltDocumentId, :FltStatusId)');
                $stmStatus->bindValue('FltDocumentId', $documentId);
                $stmStatus->bindValue('FltStatusId', 3);
                $stmStatus->execute();
                $status = $stmStatus->fetch(PDO::FETCH_ASSOC);
            }
            $pdo->commit();
            return $result;
        } catch (\PDOException | Exception $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }
}
