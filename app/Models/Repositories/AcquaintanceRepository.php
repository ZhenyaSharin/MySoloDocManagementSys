<?php

namespace App\Models\Repositories;

use App\Dto\AcquaintanceDto\AcquaintanceDto;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\AcquaintanceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use PDO;

class AcquaintanceRepository implements AcquaintanceRepositoryInterface
{
    public function list()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllAcquaintances"() WHERE "removed" IS NULL ORDER BY "id" DESC');
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

    public function listByUserId(int $userId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAcquaintancesByUserId"(:FltUserId) WHERE "removed" IS NULL ORDER BY "id" DESC');
            $stm->bindValue("FltUserId", $userId);
            $stm->execute();

            $arr = $stm->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            $stmUser = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId) WHERE "removed" IS NULL');
            $stmDoc = $pdo->prepare('SELECT "id", "description", "typeId", "created_at" FROM "GetDocumentById"(:FltDocumentId) WHERE "removed" IS NULL');
            $stmStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1');
            $stmDocType = $pdo->prepare('SELECT "title" FROM "GetDocTypeById"(:FltTypeId)');
            if (isset($arr)) {
                foreach ($arr as &$item) {
                    if (isset($item['documentId'])) {
                        $stmDoc->bindValue("FltDocumentId", $item["documentId"]);
                        $stmDoc->execute();
                        $doc = $stmDoc->fetch(PDO::FETCH_ASSOC);
                        if ($doc != false) {
                            $item['document'] = $doc;
                            $stmStatus->bindValue("FltDocumentId", $item["documentId"]);
                            $stmStatus->execute();
                            $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                            $stmDocType->bindValue("FltTypeId", $doc['typeId']);
                            $stmDocType->execute();
                            $item['documentType'] = $stmDocType->fetch(PDO::FETCH_ASSOC);
                            if (isset($item['initiatorId'])) {
                                $stmUser->bindValue("FltUserId", $item['initiatorId']);
                                $stmUser->execute();
                                $item['user'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                            }
                            $result[] = $item;
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

    public function listByUserIdNonViewed(int $userId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAcquaintancesByUserId"(:FltUserId) WHERE "removed" IS NULL AND "seen_at" IS NULL ORDER BY "id" DESC');
            $stm->bindValue("FltUserId", $userId);
            $stm->execute();
            $result = [];
            $arr = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stmUser = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId) WHERE "removed" IS NULL');
            $stmDoc = $pdo->prepare('SELECT "id", "description", "typeId", "created_at" FROM "GetDocumentById"(:FltDocumentId) WHERE "removed" IS NULL');
            $stmStatus = $pdo->prepare('SELECT "docstatusId", "docStatusTitle", "docStatusAlias", "created_at", "updated_at" FROM "GetDocStatusLogByDocumentId"(:FltDocumentId) LIMIT 1');
            $stmDocType = $pdo->prepare('SELECT "title" FROM "GetDocTypeById"(:FltTypeId)');
            if (isset($arr)) {
                foreach ($arr as &$item) {
                    if (isset($item['documentId'])) {
                        $stmDoc->bindValue("FltDocumentId", $item["documentId"]);
                        $stmDoc->execute();
                        $doc = $stmDoc->fetch(PDO::FETCH_ASSOC);
                        if ($doc != false) {
                            $item['document'] = $doc;
                            $stmStatus->bindValue("FltDocumentId", $item["documentId"]);
                            $stmStatus->execute();
                            $item['status'] = $stmStatus->fetch(PDO::FETCH_ASSOC);
                            $stmDocType->bindValue("FltTypeId", $doc['typeId']);
                            $stmDocType->execute();
                            $item['documentType'] = $stmDocType->fetch(PDO::FETCH_ASSOC);
                            if (isset($item['initiatorId'])) {
                                $stmUser->bindValue("FltUserId", $item['initiatorId']);
                                $stmUser->execute();
                                $item['user'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                            }
                            $result[] = $item;
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

    public function listByInitiatorId(int $userId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAcquaintanceByInitiatorId"(:FltInitiatorId) WHERE "removed" IS NULL ORDER BY "id" DESC');
            $stm->bindValue("FltInitiatorId", $userId);
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

    public function getById(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAcquaintanceById"(:FltId)');
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

    public function add(array $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewAcquaintance"(:FltDocumentId, :FltUserId, :FltInitiatorId)');
            $result = [];
            foreach ($data as $item) {
                $stm->bindValue("FltDocumentId", $item->documentId);
                $stm->bindValue("FltUserId", $item->userId);
                $stm->bindValue("FltInitiatorId", $item->initiatorId);
                $stm->execute();
                $res = $stm->fetch(PDO::FETCH_ASSOC);
                $stmUser = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic", "email" FROM "GetUserById"(:FltUserId) WHERE "removed" IS NULL');
                $stmUser->bindValue("FltUserId", $item->userId);
                $stmUser->execute();
                $res['user'] = $stmUser->fetch(PDO::FETCH_ASSOC);
                $result[] = $res;
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

    public function makeSeen(AcquaintanceDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdateAcquaintanceWithSeen"(:FltId)');
            $stm->bindValue("FltId", $data->id);
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

    public function listByDocumentId(int $documentId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAcquaintanceByDocumentId"(:FltDocumentId) WHERE "removed" IS NULL ORDER BY "id" DESC');
            $stm->bindValue("FltDocumentId", $documentId);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            $stmUser = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId) WHERE "removed" IS NULL');
            foreach ($result as &$item) {
                if (isset($item['userId'])) {
                    $stmUser->bindValue("FltUserId", $item['userId']);
                    $stmUser->execute();
                    $item['user'] = $stmUser->fetch(PDO::FETCH_ASSOC);
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

    public function remove(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RemoveAcquaintance"(:FltId)');
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
}
