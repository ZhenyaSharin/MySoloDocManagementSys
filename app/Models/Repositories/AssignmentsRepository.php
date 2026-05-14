<?php

namespace App\Models\Repositories;

use App\Dto\AssignmentDto\AssignmentControlDto;
use App\Dto\AssignmentDto\AssignmentDeadlineDto;
use App\Dto\AssignmentDto\AssignmentDto;
use App\Dto\AssignmentDto\AssignmentStatusDto;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\AssignmentsRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use PDO;

class AssignmentsRepository implements AssignmentsRepositoryInterface
{
    public function list()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllAssignments"() WHERE "removed" IS NULL ORDER BY "created_at" DESC');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                $stmStatus = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmStatusData = $pdo->prepare('SELECT * FROM "GetStatusById"(:FltId)');
                foreach ($result as &$item) {
                    $stmAuthor->bindValue("FltAuthorId", $item["authorId"]);
                    $stmAuthor->execute();
                    $item['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                    $stmStatus->bindValue("FltAssignmentId", $item['id']);
                    $stmStatus->execute();
                    $alias = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    if ($alias != false) {
                        $stmStatusData->bindValue("FltId", $alias['assignmentstatusId']);
                        $stmStatusData->execute();
                        $item['status'] = $stmStatusData->fetch(PDO::FETCH_ASSOC);
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

    public function listWithInfo()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllAssignments"() WHERE "removed" IS NULL ORDER BY "created_at" DESC');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (isset($result)) {
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                $stmType = $pdo->prepare('SELECT "id", "title" FROM "GetAssignmentTypeById"(:FltTypeId)');
                $stmUser = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                $stmBase = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltAssignmentId)');
                $stmDocumentBase = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltDocumentId)');
                $stmDeadline = $pdo->prepare('SELECT * FROM "GetAssignmentDeadlinesByAssignmentId"(:FltAssignmentId) WHERE "approved_at" IS NOT NULL AND "refused_at" IS NULL AND "removed" IS NULL ORDER BY "created_at" DESC');
                $stmStatus = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmStatusData = $pdo->prepare('SELECT * FROM "GetStatusById"(:FltId)');
                foreach ($result as &$item) {

                    $stmAuthor->bindValue("FltAuthorId", $item['authorId']);
                    $stmAuthor->execute();
                    $item['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);

                    $stmDeadline->bindValue("FltAssignmentId", $item["id"]);
                    $stmDeadline->execute();
                    $item['deadline'] = $stmDeadline->fetchAll(PDO::FETCH_ASSOC);

                    $stmType->bindValue("FltTypeId", $item["typeId"]);
                    $stmType->execute();
                    $item['type'] = $stmType->fetch(PDO::FETCH_ASSOC);

                    $stmUser->bindValue("FltUserId", $item["executorId"]);
                    $stmUser->execute();
                    $item['executor'] = $stmUser->fetch(PDO::FETCH_ASSOC);

                    if ($item['baseId'] != null) {
                        $stmBase->bindValue('FltAssignmentId', $item['baseId']);
                        $stmBase->execute();
                        $item['base'] = $stmBase->fetch(PDO::FETCH_ASSOC);
                    } else if ($item['documentId']) {
                        $stmDocumentBase->bindValue('FltDocumentId', $item['documentId']);
                        $stmDocumentBase->execute();
                        $item['documentBase'] = $stmDocumentBase->fetch(PDO::FETCH_ASSOC);
                    }

                    $stmUser->bindValue("FltUserId", $item["executorId"]);
                    $stmUser->execute();
                    $item['executor'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                    $stmStatus->bindValue("FltAssignmentId", $item['id']);
                    $stmStatus->execute();
                    $alias = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    if ($alias != false) {
                        $stmStatusData->bindValue("FltId", $alias['assignmentstatusId']);
                        $stmStatusData->execute();
                        $item['status'] = $stmStatusData->fetch(PDO::FETCH_ASSOC);
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

    public function add(AssignmentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewAssignment"(:FltDocumentId, :FltTypeId, :FltAuthorId, :FltText, :FltUserId, :FltBaseId, :FltMainId, :FltDescription)');
            $stmDeadline = $pdo->prepare('SELECT * FROM "AddNewAssignmentDeadline"(:FltAssignmentId, :FltInitiatorId, :FltApprovedUserId, :FltDeadline, :FltInitiatedAt, :FltApprovedAt, :FltComment, :FltFileId)');
            $stmMail = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltId)');
            $stmStatus = $pdo->prepare('SELECT * FROM "AddAssignmentAndAssignmentStatus"(:FltAssignmentId, :FltAssignmentStatusId, :FltNote)');
            $stmMain = $pdo->prepare('SELECT * FROM "UpdateAssignmentWithMainId"(:FltId, :FltMainId)');
            $result = [];
            for ($i = 0; $i < count($data->executors); $i++) {
                $res = [];
                if ($i === 0) {
                    $stm->bindValue("FltDocumentId", $data->documentId ?? null);
                    $stm->bindValue("FltBaseId", $data->baseId ?? null);
                    $stm->bindValue("FltTypeId", $data->typeId);
                    $stm->bindValue("FltAuthorId", $data->authorId);
                    $stm->bindValue("FltUserId", $data->executors[$i]['id']);
                    $stm->bindValue("FltText", $data->text);
                    $stm->bindValue("FltMainId", null);
                    $stm->bindValue("FltDescription", $data->description);
                    $stm->execute();
                    $res = $stm->fetch(PDO::FETCH_ASSOC);
                    if (count($data->executors) > 1) {
                        if ($res != false) {
                            $stmMain->bindValue("FltId", $res['id']);
                            $stmMain->bindValue("FltMainId", $res['id']);
                            $stmMain->execute();
                            $resMain = $stmMain->fetch(PDO::FETCH_ASSOC);
                            $res = $resMain;
                        }
                    }
                    $stmMail->bindValue("FltId", $data->executors[$i]['id']);
                    $stmMail->execute();
                    $res['email'] = $stmMail->fetch(PDO::FETCH_ASSOC);
                } else {
                    $stm->bindValue("FltDocumentId", $data->documentId ?? null);
                    $stm->bindValue("FltBaseId", $data->baseId ?? null);
                    $stm->bindValue("FltTypeId", $data->typeId);
                    $stm->bindValue("FltAuthorId", $data->authorId);
                    $stm->bindValue("FltUserId", $data->executors[$i]['id']);
                    $stm->bindValue("FltText", $data->text);
                    $stm->bindValue("FltMainId", $result[0]['id']);
                    $stm->bindValue("FltDescription", $data->description);
                    $stm->execute();
                    $res = $stm->fetch(PDO::FETCH_ASSOC);
                    $stmMail->bindValue("FltId", $data->executors[$i]['id']);
                    $stmMail->execute();
                    $res['email'] = $stmMail->fetch(PDO::FETCH_ASSOC);
                }
                $res['deadline'] = [];
                if ($res != false) {
                    $stmDeadline->bindValue("FltAssignmentId", $res['id']);
                    $stmDeadline->bindValue("FltInitiatorId", $data->authorId);
                    $stmDeadline->bindValue("FltApprovedUserId", $data->authorId);
                    $stmDeadline->bindValue("FltDeadline", $data->deadline);
                    $stmDeadline->bindValue("FltInitiatedAt", now());
                    $stmDeadline->bindValue("FltApprovedAt", now());
                    $stmDeadline->bindValue("FltComment", null);
                    $stmDeadline->bindValue("FltFileId", null);
                    $stmDeadline->execute();
                    $res['deadline'] = $stmDeadline->fetch(PDO::FETCH_ASSOC);

                    $stmStatus->bindValue("FltAssignmentId", $res['id']);
                    $stmStatus->bindValue("FltAssignmentStatusId", 6);
                    $stmStatus->bindValue("FltNote", null);
                    $stmStatus->execute();
                    $res['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                }
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

    public function addWithAddition(AssignmentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewAssignment"(:FltDocumentId, :FltTypeId, :FltAuthorId, :FltText, :FltUserId, :FltBaseId, :FltMainId, :FltDescription)');
            $stmDeadline = $pdo->prepare('SELECT * FROM "AddNewAssignmentDeadline"(:FltAssignmentId, :FltInitiatorId, :FltApprovedUserId, :FltDeadline, :FltInitiatedAt, :FltApprovedAt, :FltComment, :FltFileId)');
            $stmMail = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltId)');
            $stmStatus = $pdo->prepare('SELECT * FROM "AddAssignmentAndAssignmentStatus"(:FltAssignmentId, :FltAssignmentStatusId, :FltNote)');
            $stmMain = $pdo->prepare('SELECT * FROM "UpdateAssignmentWithMainId"(:FltId, :FltMainId)');
            $stmFile = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
            $stmAdd = $pdo->prepare('SELECT * FROM "AddFileAndAddition"(:FltFileId, :FltDocumentId, :FltAssignmentId, :FltFeedbackId, :FltBlogId, :FltAgreementAndUserId)');
            $result = [];
            for ($i = 0; $i < count($data->executors); $i++) {
                $res = [];
                if ($i === 0) {
                    $stm->bindValue("FltDocumentId", $data->documentId ?? null);
                    $stm->bindValue("FltBaseId", $data->baseId ?? null);
                    $stm->bindValue("FltTypeId", $data->typeId);
                    $stm->bindValue("FltAuthorId", $data->authorId);
                    $stm->bindValue("FltUserId", $data->executors[$i]['id']);
                    $stm->bindValue("FltText", $data->text);
                    $stm->bindValue("FltMainId", null);
                    $stm->bindValue("FltDescription", $data->description);
                    $stm->execute();
                    $res = $stm->fetch(PDO::FETCH_ASSOC);
                    if (count($data->executors) > 1) {
                        if ($res != false) {
                            $stmMain->bindValue("FltId", $res['id']);
                            $stmMain->bindValue("FltMainId", $res['id']);
                            $stmMain->execute();
                            $resMain = $stmMain->fetch(PDO::FETCH_ASSOC);
                            $res = $resMain;
                        }
                    }
                    $stmMail->bindValue("FltId", $data->executors[$i]['id']);
                    $stmMail->execute();
                    $res['email'] = $stmMail->fetch(PDO::FETCH_ASSOC);
                } else {
                    $stm->bindValue("FltDocumentId", $data->documentId ?? null);
                    $stm->bindValue("FltBaseId", $data->baseId ?? null);
                    $stm->bindValue("FltTypeId", $data->typeId);
                    $stm->bindValue("FltAuthorId", $data->authorId);
                    $stm->bindValue("FltUserId", $data->executors[$i]['id']);
                    $stm->bindValue("FltText", $data->text);
                    $stm->bindValue("FltMainId", $result[0]['id']);
                    $stm->bindValue("FltDescription", $data->description);
                    $stm->execute();
                    $res = $stm->fetch(PDO::FETCH_ASSOC);
                    $stmMail->bindValue("FltId", $data->executors[$i]['id']);
                    $stmMail->execute();
                    $res['email'] = $stmMail->fetch(PDO::FETCH_ASSOC);
                }
                $res['deadline'] = [];
                if ($res != false) {
                    $stmDeadline->bindValue("FltAssignmentId", $res['id']);
                    $stmDeadline->bindValue("FltInitiatorId", $data->authorId);
                    $stmDeadline->bindValue("FltApprovedUserId", $data->authorId);
                    $stmDeadline->bindValue("FltDeadline", $data->deadline);
                    $stmDeadline->bindValue("FltInitiatedAt", now());
                    $stmDeadline->bindValue("FltApprovedAt", now());
                    $stmDeadline->bindValue("FltComment", null);
                    $stmDeadline->bindValue("FltFileId", null);
                    $stmDeadline->execute();
                    $res['deadline'] = $stmDeadline->fetch(PDO::FETCH_ASSOC);

                    $stmStatus->bindValue("FltAssignmentId", $res['id']);
                    $stmStatus->bindValue("FltAssignmentStatusId", 6);
                    $stmStatus->bindValue("FltNote", null);
                    $stmStatus->execute();
                    $res['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                }
                $fileArr = [];
                foreach ($data->files as $item) {
                    $stmFile->bindValue("FltFile", $item->fileLink);
                    $stmFile->bindValue("FltFormat", $item->format);
                    $stmFile->bindValue("FltType", 1);
                    $stmFile->bindValue("FltComment", $item->comment);
                    $stmFile->execute();
                    $file = $stmFile->fetch(PDO::FETCH_ASSOC);

                    if (count($file) > 0) {
                        $stmAdd->bindValue("FltFileId", $file['id']);
                        $stmAdd->bindValue("FltDocumentId", null);
                        $stmAdd->bindValue("FltAssignmentId", $res['id']);
                        $stmAdd->bindValue("FltFeedbackId", null);
                        $stmAdd->bindValue("FltBlogId", null);
                        $stmAdd->bindValue("FltAgreementAndUserId", null);
                        $stmAdd->execute();
                        $fileArr = $stmAdd->fetch(PDO::FETCH_ASSOC);
                        $fileArr['file'] = $file;
                        $res['files'] = $fileArr;
                    }
                }
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

    public function listAssignmentTypes()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT "id", "title" FROM "GetAssignmentTypes"() WHERE "removed" IS NULL');
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

    public function listByAuthorId(int $authorId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAssignmentsByAuthorId"(:FltAuthorId) WHERE "removed" IS NULL ORDER BY "created_at" DESC');
            $stm->bindValue("FltAuthorId", $authorId);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetAssignmentTypeById"(:FltId)');
                $stmUser = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                $stmBase = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltAssignmentId)');
                $stmDocumentBase = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltDocumentId)');
                $stmDeadline = $pdo->prepare('SELECT * FROM "GetAssignmentDeadlinesByAssignmentId"(:FltAssignmentId) WHERE "removed" IS NULL ORDER BY "created_at" DESC');
                $stmStatus = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmStatusData = $pdo->prepare('SELECT * FROM "GetStatusById"(:FltId)');
                foreach ($result as &$item) {
                    $stmDeadline->bindValue("FltAssignmentId", $item["id"]);
                    $stmDeadline->execute();
                    $item['deadline'] = $stmDeadline->fetchAll(PDO::FETCH_ASSOC);
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['type'] = $stmType->fetch(PDO::FETCH_ASSOC);
                    $stmUser->bindValue("FltUserId", $item["executorId"]);
                    $stmUser->execute();
                    $item['executor'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                    if ($item['baseId'] != null) {
                        $stmBase->bindValue('FltAssignmentId', $item['baseId']);
                        $stmBase->execute();
                        $item['base'] = $stmBase->fetch(PDO::FETCH_ASSOC);
                    } else if ($item['documentId']) {
                        $stmDocumentBase->bindValue('FltDocumentId', $item['documentId']);
                        $stmDocumentBase->execute();
                        $item['documentBase'] = $stmDocumentBase->fetch(PDO::FETCH_ASSOC);
                    }
                    $stmStatus->bindValue("FltAssignmentId", $item['id']);
                    $stmStatus->execute();
                    $alias = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    if ($alias != false) {
                        $stmStatusData->bindValue("FltId", $alias['assignmentstatusId']);
                        $stmStatusData->execute();
                        $item['status'] = $stmStatusData->fetch(PDO::FETCH_ASSOC);
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

    public function listByAuthorIdWithCount(int $authorId, int $count)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAssignmentsByAuthorId"(:FltAuthorId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT :FltCount');
            $stm->bindValue("FltAuthorId", $authorId);
            $stm->bindValue("FltCount", $count);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetAssignmentTypeById"(:FltId)');
                $stmUser = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                $stmBase = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltAssignmentId)');
                $stmDocumentBase = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltDocumentId)');
                $stmDeadline = $pdo->prepare('SELECT * FROM "GetAssignmentDeadlinesByAssignmentId"(:FltAssignmentId) WHERE "removed" IS NULL ORDER BY "created_at" DESC');
                $stmStatus = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmStatusData = $pdo->prepare('SELECT "id", "title" FROM "GetStatusById"(:FltId)');
                foreach ($result as &$item) {
                    $stmDeadline->bindValue("FltAssignmentId", $item["id"]);
                    $stmDeadline->execute();
                    $item['deadline'] = $stmDeadline->fetchAll(PDO::FETCH_ASSOC);
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['type'] = $stmType->fetch(PDO::FETCH_ASSOC);
                    $stmUser->bindValue("FltUserId", $item["executorId"]);
                    $stmUser->execute();
                    $item['executor'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                    if ($item['baseId'] != null) {
                        $stmBase->bindValue('FltAssignmentId', $item['baseId']);
                        $stmBase->execute();
                        $item['base'] = $stmBase->fetch(PDO::FETCH_ASSOC);
                    } else if ($item['documentId']) {
                        $stmDocumentBase->bindValue('FltDocumentId', $item['documentId']);
                        $stmDocumentBase->execute();
                        $item['documentBase'] = $stmDocumentBase->fetch(PDO::FETCH_ASSOC);
                    }
                    $stmStatus->bindValue("FltAssignmentId", $item['id']);
                    $stmStatus->execute();
                    $alias = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    if ($alias != false) {
                        $stmStatusData->bindValue("FltId", $alias['assignmentstatusId']);
                        $stmStatusData->execute();
                        $item['status'] = $stmStatusData->fetch(PDO::FETCH_ASSOC);
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

    public function listByExecutorId(int $executorId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAssignmentsByExecutorId"(:FltExecutorId) WHERE "removed" IS NULL ORDER BY "created_at" DESC');
            $stm->bindValue("FltExecutorId", $executorId);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetAssignmentTypeById"(:FltId)');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                $stmBase = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltAssignmentId)');
                $stmDocumentBase = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltDocumentId)');
                $stmDeadline = $pdo->prepare('SELECT * FROM "GetAssignmentDeadlinesByAssignmentId"(:FltAssignmentId) WHERE "approved_at" IS NOT NULL AND "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmStatus = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmStatusData = $pdo->prepare('SELECT "id", "title" FROM "GetStatusById"(:FltId)');
                foreach ($result as &$item) {
                    $stmDeadline->bindValue("FltAssignmentId", $item["id"]);
                    $stmDeadline->execute();
                    $item['deadline'] = $stmDeadline->fetchAll(PDO::FETCH_ASSOC);
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['type'] = $stmType->fetch(PDO::FETCH_ASSOC);
                    $stmAuthor->bindValue("FltUserId", $item["authorId"]);
                    $stmAuthor->execute();
                    $item['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                    if ($item['baseId'] != null) {
                        $stmBase->bindValue('FltAssignmentId', $item['baseId']);
                        $stmBase->execute();
                        $item['base'] = $stmBase->fetch(PDO::FETCH_ASSOC);
                    } else if ($item['documentId']) {
                        $stmDocumentBase->bindValue('FltDocumentId', $item['documentId']);
                        $stmDocumentBase->execute();
                        $item['documentBase'] = $stmDocumentBase->fetch(PDO::FETCH_ASSOC);
                    }
                    $stmStatus->bindValue("FltAssignmentId", $item['id']);
                    $stmStatus->execute();
                    $alias = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    if ($alias != false) {
                        $stmStatusData->bindValue("FltId", $alias['assignmentstatusId']);
                        $stmStatusData->execute();
                        $item['status'] = $stmStatusData->fetch(PDO::FETCH_ASSOC);
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

    public function listByExecutorIdWithCount(int $executorId, int $count)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAssignmentsByExecutorId"(:FltExecutorId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT :FltCount');
            $stm->bindValue("FltExecutorId", $executorId);
            $stm->bindValue("FltCount", $count);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetAssignmentTypeById"(:FltId)');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                $stmBase = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltAssignmentId)');
                $stmDocumentBase = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltDocumentId)');
                $stmDeadline = $pdo->prepare('SELECT * FROM "GetAssignmentDeadlinesByAssignmentId"(:FltAssignmentId) WHERE "approved_at" IS NOT NULL AND "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmStatus = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmStatusData = $pdo->prepare('SELECT * FROM "GetStatusById"(:FltId)');
                foreach ($result as &$item) {
                    $stmDeadline->bindValue("FltAssignmentId", $item["id"]);
                    $stmDeadline->execute();
                    $item['deadline'] = $stmDeadline->fetchAll(PDO::FETCH_ASSOC);
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['type'] = $stmType->fetch(PDO::FETCH_ASSOC);
                    $stmAuthor->bindValue("FltUserId", $item["authorId"]);
                    $stmAuthor->execute();
                    $item['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                    if ($item['baseId'] != null) {
                        $stmBase->bindValue('FltAssignmentId', $item['baseId']);
                        $stmBase->execute();
                        $item['base'] = $stmBase->fetch(PDO::FETCH_ASSOC);
                    } else if ($item['documentId']) {
                        $stmDocumentBase->bindValue('FltDocumentId', $item['documentId']);
                        $stmDocumentBase->execute();
                        $item['documentBase'] = $stmDocumentBase->fetch(PDO::FETCH_ASSOC);
                    }
                    $stmStatus->bindValue("FltAssignmentId", $item['id']);
                    $stmStatus->execute();
                    $alias = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    if ($alias != false) {
                        $stmStatusData->bindValue("FltId", $alias['assignmentstatusId']);
                        $stmStatusData->execute();
                        $item['status'] = $stmStatusData->fetch(PDO::FETCH_ASSOC);
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

    public function makeViewedAssignment(AssignmentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "ViewAssignmentById"(:FltId)');
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

    public function getById(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltId)');
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

    public function getByIdVersions(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltId)');
            $stm->bindValue("FltId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if (isset($result)) {
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                $stmAuthor->bindValue("FltAuthorId", $result['authorId']);
                $stmAuthor->execute();
                $result['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                // $result['surname'] = $author['surname'];
                // $result['firstname'] = $author['firstname'];
                // $result['patronymic'] = $author['patronymic'];

                $stmType = $pdo->prepare('SELECT "id", "title" FROM "GetAssignmentTypeById"(:FltTypeId)');
                $stmType->bindValue("FltTypeId", $result['typeId']);
                $stmType->execute();
                $result['type'] = $stmType->fetch(PDO::FETCH_ASSOC)['title'];
                $stmStatus = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmStatusData = $pdo->prepare('SELECT "id", "title" FROM "GetStatusById"(:FltId)');
                $stmStatus->bindValue("FltAssignmentId", $result['id']);
                $stmStatus->execute();
                $alias = $stmStatus->fetch(PDO::FETCH_ASSOC);
                if ($alias != false) {
                    $stmStatusData->bindValue("FltId", $alias['assignmentstatusId']);
                    $stmStatusData->execute();
                    $result['status'] = $stmStatusData->fetch(PDO::FETCH_ASSOC);
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

    public function getByIdWithInfo(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltId)');
            $stm->bindValue("FltId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            $result['base'] = null;
            $result['documentBase'] = null;
            if (isset($result)) {
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
                $stmAuthor->bindValue("FltAuthorId", $result['authorId']);
                $stmAuthor->execute();
                $result['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                $stmUser = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                $stmUser->bindValue("FltUserId", $result['executorId']);
                $stmUser->execute();
                $result['executor'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                if ($result['documentId']) {
                    $stmDoc = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltDocId)');
                    $stmDoc->bindValue("FltDocId", $result['documentId']);
                    $stmDoc->execute();
                    $result['documentBase'] = $stmDoc->fetch(PDO::FETCH_ASSOC);
                    if ($result['documentBase']) {
                        $stmAuthor->bindValue("FltAuthorId", $result['documentBase']['authorId']);
                        $stmAuthor->execute();
                        $result['documentBase']['authorData'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                        $stmBaseDocStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocId) LIMIT 1 ');
                        $stmBaseDocStatus->bindValue("FltDocId", $result["documentId"]);
                        $stmBaseDocStatus->execute();
                        $result['documentBase']['status'] = $stmBaseDocStatus->fetch(PDO::FETCH_ASSOC);
                    }
                }
                if ($result['baseId']) {
                    $stmBase = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltBaseId)');
                    $stmBase->bindValue("FltBaseId", $result['baseId']);
                    $stmBase->execute();
                    $result['base'] = $stmBase->fetch(PDO::FETCH_ASSOC);
                    if ($result['base']) {
                        $stmAuthor->bindValue("FltAuthorId", $result['base']['authorId']);
                        $stmAuthor->execute();
                        $result['base']['authorData'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                    }
                }
                $stmType = $pdo->prepare('SELECT "id", "title" FROM "GetAssignmentTypeById"(:FltTypeId)');
                $stmType->bindValue("FltTypeId", $result['typeId']);
                $stmType->execute();
                $result['typeName'] = $stmType->fetch(PDO::FETCH_ASSOC);
                $stmDeadline = $pdo->prepare('SELECT * FROM "GetAssignmentDeadlinesByAssignmentId"(:FltAssignmentId) WHERE "removed" IS NULL ORDER BY "created_at" DESC');
                $stmDeadline->bindValue("FltAssignmentId", $id);
                $stmDeadline->execute();
                $result['deadline'] = $stmDeadline->fetchAll(PDO::FETCH_ASSOC);
                $stmStatus = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmStatusData = $pdo->prepare('SELECT "id", "title" FROM "GetStatusById"(:FltId)');
                $stmStatus->bindValue("FltAssignmentId", $result['id']);
                $stmStatus->execute();
                $alias = $stmStatus->fetch(PDO::FETCH_ASSOC);
                if ($alias != false) {
                    $stmStatusData->bindValue("FltId", $alias['assignmentstatusId']);
                    $stmStatusData->execute();
                    $result['status'] = $stmStatusData->fetch(PDO::FETCH_ASSOC);
                }
                $stmControl = $pdo->prepare('SELECT * FROM "GetControlByAssignmentId"(:FltAssignmentId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmControl->bindValue("FltAssignmentId", $id);
                $stmControl->execute();
                $result['control'] = $stmControl->fetch(PDO::FETCH_ASSOC);
                if ($result['control'] != false) {
                    $stmController = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                    $stmController->bindValue("FltUserId", $result['control']['userId']);
                    $stmController->execute();
                    $result['control']['user'] = $stmController->fetch(PDO::FETCH_ASSOC);
                    $stmInitiator = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltInitiatorId)');
                    $stmInitiator->bindValue("FltInitiatorId", $result['control']['initiatorId']);
                    $stmInitiator->execute();
                    $result['control']['initiator'] = $stmInitiator->fetch(PDO::FETCH_ASSOC);
                }
                // здесь был статус-лог
                $stmAddFile = $pdo->prepare('SELECT * FROM "GetFileAndAdditionByAssignmentId"(:FltAssignmentId) WHERE "removed" IS NULL ORDER BY "created_at" DESC');
                $stmAddFile->bindValue("FltAssignmentId", $id);
                $stmAddFile->execute();
                $addFiles = $stmAddFile->fetchAll(PDO::FETCH_ASSOC);
                $result['fileAddition'] = [];
                if ($addFiles != []) {
                    $stmAddFileFile = $pdo->prepare('SELECT * FROM "GetFileById"(:FltId)');
                    foreach ($addFiles as $item) {
                        $stmAddFileFile->bindValue("FltId", $item['fileId']);
                        $stmAddFileFile->execute();
                        $file = $stmAddFileFile->fetch(PDO::FETCH_ASSOC);
                        $file['addFile'] = $item;
                        $result['fileAddition'][] = $file;
                    };
                };
                $byMain = [];
                if ($result['mainId'] != null) {
                    $stm2 = $pdo->prepare('SELECT * FROM "GetAssignmentsByMainId"(:FltId) ORDER BY "id" DESC');
                    $stm2->bindValue("FltId", $result['mainId']);
                    $stm2->execute();
                    $res = $stm2->fetchAll(PDO::FETCH_ASSOC);
                    if ($res != []) {
                        foreach ($res as &$item) {
                            $stmUser->bindValue("FltUserId", $item['executorId']);
                            $stmUser->execute();
                            $item['executor'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                            $stmStatus->bindValue("FltAssignmentId", $item['id']);
                            $stmStatus->execute();
                            $alias = $stmStatus->fetch(PDO::FETCH_ASSOC);
                            if ($alias != false) {
                                $stmStatusData->bindValue("FltId", $alias['assignmentstatusId']);
                                $stmStatusData->execute();
                                $item['status'] = $stmStatusData->fetch(PDO::FETCH_ASSOC);
                                $item['status']['created_at'] = $alias['created_at'];
                            }
                            $byMain[] = $item;
                        }
                    }
                } else {
                    $byMain = [];
                }
                $result['byMain'] = $byMain;
                $stmLog = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC');
                $stmLog->bindValue("FltAssignmentId", $id);
                $stmLog->execute();
                $statusLog = $stmLog->fetchAll(PDO::FETCH_ASSOC);
                if (isset($statusLog)) {
                    foreach ($statusLog as &$item) {
                        $stmStatusData->bindValue("FltId", $item['assignmentstatusId']);
                        $stmStatusData->execute();
                        $item['status'] = $stmStatusData->fetch(PDO::FETCH_ASSOC);
                    }
                    $result['statusLog'] = $statusLog;
                };
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

    public function listNonViewedByExecutorId(int $executorId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT "id" FROM "GetAssignmentsByExecutorId"(:FltExecutorId) WHERE "removed" IS NULL AND "viewed_at" IS NULL ORDER BY "created_at" DESC');
            $stm->bindValue("FltExecutorId", $executorId);
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

    public function updateStatusByAlias(AssignmentStatusDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stmAlias = $pdo->prepare('SELECT * FROM "GetStatusByAlias"(:FltAlias)');
            $stmAlias->bindValue("FltAlias", $data->alias);
            $stmAlias->execute();
            $alias = $stmAlias->fetch(PDO::FETCH_ASSOC);
            if ($alias != false) {
                $stm = $pdo->prepare('SELECT * FROM "AddAssignmentAndAssignmentStatus"(:FltAssignmentId, :FltAssignmentStatusId, :FltNote)');
                $stm->bindValue("FltAssignmentId", $data->assignmentId);
                $stm->bindValue("FltAssignmentStatusId", $alias['id']);
                $stm->bindValue("FltNote", $data->note);
                $stm->execute();
                $result = $stm->fetch(PDO::FETCH_ASSOC);
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

    public function updateStatus(AssignmentStatusDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddAssignmentAndAssignmentStatus"(:FltAssignmentId, :FltAssignmentStatusId, :FltNote)');
            $stm->bindValue("FltAssignmentId", $data->assignmentId);
            $stm->bindValue("FltAssignmentStatusId", $data->statusId);
            $stm->bindValue("FltNoteId", $data->note);
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

    public function listLog(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC');
            $stm->bindValue("FltAssignmentId", $id);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (isset($result)) {
                $stmStatus = $pdo->prepare('SELECT "id", "title" FROM "GetStatusById"(:FltId)');
                foreach ($result as &$item) {
                    $stmStatus->bindValue("FltId", $item['assignmentstatusId']);
                    $stmStatus->execute();
                    $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
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

    public function updateStatusWithNewDeadline(AssignmentStatusDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddAssignmentAndAssignmentStatus"(:FltAssignmentId, :FltAssignmentStatusId, :FltNote)');
            $stm->bindValue("FltAssignmentId", $data->assignmentId);
            $stm->bindValue("FltAssignmentStatusId", 7);
            $stm->bindValue("FltNote", $data->note);
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

    public function addControl(AssignmentControlDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddControl"(:FltUserId, :FltAssignmentId, :FltInitiatorId)');
            $stm->bindValue("FltUserId", $data->userId);
            $stm->bindValue("FltAssignmentId", $data->assignmentId);
            $stm->bindValue("FltInitiatorId", $data->initiatorId);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmUser = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic", "email" FROM "GetUserById"(:FltUserId) WHERE "removed" IS NULL');
                $stmUser->bindValue("FltUserId", $data->userId);
                $stmUser->execute();
                $result['user'] = $stmUser->fetch(PDO::FETCH_ASSOC);
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

    public function viewControl(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "ViewControlById"(:FltId)');
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

    public function controlLastByAssignmentId(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetControlByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
            $stm->bindValue("FltAssignmentId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmController = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                $stmController->bindValue("FltUserId", $result['userId']);
                $stmController->execute();
                $result['user'] = $stmController->fetch(PDO::FETCH_ASSOC);
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

    // Доделать

    public function controlsByUserId(int $userId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stmControl = $pdo->prepare('SELECT * FROM "GetAssignmentControlByUserId"(:FltUserId) WHERE "removed" IS NULL');
            $stmControl->bindValue("FltUserId", $userId);
            $stmControl->execute();
            $res = $stmControl->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            foreach ($res as $value) {
                $stm = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltId)');
                $stm->bindValue("FltId", $value['assignmentId']);
                $stm->execute();
                $result[] = $stm->fetch(PDO::FETCH_ASSOC);
            }
            if (count($result) > 0) {
                $stmType = $pdo->prepare('SELECT "id",
                "title" FROM "GetAssignmentTypeById"(:FltId)');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                $stmBase = $pdo->prepare('SELECT * FROM "GetAssignmentById"(:FltAssignmentId)');
                $stmDocumentBase = $pdo->prepare('SELECT * FROM "GetDocumentById"(:FltDocumentId)');
                $stmDeadline = $pdo->prepare('SELECT * FROM "GetAssignmentDeadlinesByAssignmentId"(:FltAssignmentId) WHERE "approved_at" IS NOT NULL AND "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmStatus = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmStatusData = $pdo->prepare('SELECT * FROM "GetStatusById"(:FltId)');
                foreach ($result as &$item) {
                    $stmDeadline->bindValue("FltAssignmentId", $item["id"]);
                    $stmDeadline->execute();
                    $item['deadline'] = $stmDeadline->fetchAll(PDO::FETCH_ASSOC);
                    $stmType->bindValue("FltId", $item["typeId"]);
                    $stmType->execute();
                    $item['type'] = $stmType->fetch(PDO::FETCH_ASSOC);
                    $stmAuthor->bindValue("FltUserId", $item["authorId"]);
                    $stmAuthor->execute();
                    $item['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                    if ($item['baseId'] != null) {
                        $stmBase->bindValue('FltAssignmentId', $item['baseId']);
                        $stmBase->execute();
                        $item['base'] = $stmBase->fetch(PDO::FETCH_ASSOC);
                    } else if ($item['documentId']) {
                        $stmDocumentBase->bindValue('FltDocumentId', $item['documentId']);
                        $stmDocumentBase->execute();
                        $item['documentBase'] = $stmDocumentBase->fetch(PDO::FETCH_ASSOC);
                    }
                    $stmStatus->bindValue("FltAssignmentId", $item['id']);
                    $stmStatus->execute();
                    $alias = $stmStatus->fetch(PDO::FETCH_ASSOC);
                    if ($alias != false) {
                        $stmStatusData->bindValue("FltId", $alias['assignmentstatusId']);
                        $stmStatusData->execute();
                        // echo 'wqdqwd';
                        $item['status'] = $stmStatusData->fetch(PDO::FETCH_ASSOC);
                    }
                    $stmControl = $pdo->prepare('SELECT * FROM "GetControlByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
                    $stmControl->bindValue("FltAssignmentId", $item['id']);
                    $stmControl->execute();
                    $item['control'] = $stmControl->fetch(PDO::FETCH_ASSOC);
                    if ($item['control'] != false) {
                        $stmInitiator = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltInitiatorId)');
                        $stmInitiator->bindValue("FltInitiatorId", $item['control']['initiatorId']);
                        $stmInitiator->execute();
                        $item['control']['initiator'] = $stmInitiator->fetch(PDO::FETCH_ASSOC);
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

    public function approveDeadline(int $id, int $userId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "ApproveAssignmentDeadline"(:FltId, :FltApproverId) ');
            $stm->bindValue("FltId", $id);
            $stm->bindValue("FltApproverId", $userId);
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

    public function refuseDeadline(int $id, int $userId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RefuseAssignmentDeadline"(:FltId, :FltApproverId) ');
            $stm->bindValue("FltId", $id);
            $stm->bindValue("FltApproverId", $userId);
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

    public function addDeadline(AssignmentDeadlineDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewAssignmentDeadline"(:FltAssignmentId, :FltInitiatorId, :FltApprovedUserId, :FltDeadline, :FltInitiatedAt, :FltApprovedAt, :FltComment, :FltFileId)');
            $stm->bindValue("FltAssignmentId", $data->assignmentId);
            $stm->bindValue("FltInitiatorId", $data->initiatorId);
            $stm->bindValue("FltApprovedUserId", null);
            $stm->bindValue("FltDeadline", $data->deadline);
            $stm->bindValue("FltInitiatedAt", now());
            $stm->bindValue("FltApprovedAt", null);
            $stm->bindValue("FltComment", $data->comment);
            $stm->bindValue("FltFileId", null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmAuthor = $pdo->prepare('SELECT "id", "email" FROM "GetUserById"(:FltAuthorId)');
                $stmUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId)');
                $stmAuthor->bindValue("FltAuthorId", $data->authorId);
                $stmAuthor->execute();
                $result['author'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);

                $stmUser->bindValue("FltUserId", $data->initiatorId);
                $stmUser->execute();
                $result['initiator'] = $stmUser->fetch(PDO::FETCH_ASSOC);
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

    public function listAssignStatuses()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetStatusesByGroup"(:FltGroup) WHERE "removed" IS NULL ORDER BY "id"');
            $stm->bindValue("FltGroup", 2);
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

    public function updateInfo(AssignmentDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdateAssignmentInfo"(:FltAssignmentId, :FltTypeId, :FltText, :FltDescription, :FltBaseId, :FltDocumentId)');
            $stm->bindValue("FltAssignmentId", $data->id);
            $stm->bindValue("FltTypeId", $data->typeId);
            $stm->bindValue("FltText", $data->text);
            $stm->bindValue("FltDescription", $data->description);
            $stm->bindValue("FltBaseId", $data->baseId);
            $stm->bindValue("FltDocumentId", $data->documentId);
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
}
