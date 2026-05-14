<?php

namespace App\Models\Repositories;

use App\Dto\RelationDto\RelationDto;
use App\Dto\RelationDto\RelationArrayDto;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\RelationsRepositoryInterface;
use Illuminate\Support\Facades\DB;
use PDO;
use Throwable;

class RelationsRepository implements RelationsRepositoryInterface
{

	public function list(string $ascDesc = 'DESC')
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllRelations"() WHERE "removed" IS NULL ORDER BY "id"'. $ascDesc);
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

    public function getById($id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetRelationById"(:FltId)');
            $stm->bindValue("FltId", $id);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                $stmDoc = $pdo->prepare('SELECT "id", "description", "creationDate", "orderNum", "typeId" FROM "GetDocumentById"(:FltDocumentId1) WHERE "removed" IS NULL');
                $stmAssign = $pdo->prepare('SELECT "id", "text", "description" FROM "GetAssignmentById"(:FltAssignmentId1) WHERE "removed" IS NULL');
                $stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId) WHERE "removed" IS NULL');
                $stmDocType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltDocTypeId)');
                $stmAssignType = $pdo->prepare('SELECT "id", "title" FROM "GetAssignmentTypeById"(:FltAssignTypeId)');
                foreach ($result as &$item) {
                    $stmAuthor->bindValue("FltAuthorId", $item['userId']);
                    $stmAuthor->execute();
                    $item['authorId'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                    $item['documentData1'] = null;
                    $item['documentData2'] = null;
                    $item['assignmentData1'] = null;
                    $item['assignmentData2'] = null;
                    if (isset($item['documentId1'])) {
                        $stmDoc->bindValue("FltDocumentId1", $item['documentId1']);
                        $stmDoc->execute();
                        $item['documentData1'] = $stmDoc->fetch(PDO::FETCH_ASSOC);
                        $stmDocType->bindValue("FltDocTypeId", $item['documentData1']['typeId']);
                    }
                    if (isset($item['documentId2'])) {
                        $stmDoc->bindValue("FltDocumentId2", $item['documentId2']);
                        $stmDoc->execute();
                        $item['documentData2'] = $stmDoc->fetch(PDO::FETCH_ASSOC);
                        $stmDocType->bindValue("FltDocTypeId", $item['documentData2']['typeId']);
                    }
                    if (isset($item['assignmentId1'])) {
                        $stmAssign->bindValue("FltAssignmentId1", $item['assignmentId1']);
                        $stmAssign->execute();
                        $item['assignmentData1'] = $stmAssign->fetch(PDO::FETCH_ASSOC);
                        $stmAssignType->bindValue("FltAssignTypeId", $item['assignmentData2']['typeId']);   
                    }
                    if (isset($item['assignmentId2'])) {
                        $stmAssign->bindValue("FltAssignmentId2", $item['assignmentId2']);
                        $stmAssign->execute();
                        $item['assignmentData2'] = $stmAssign->fetch(PDO::FETCH_ASSOC);
                        $stmAssignType->bindValue("FltAssignTypeId", $item['assignmentData2']['typeId']);
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

    public function listByDocId($documentId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('WITH RECURSIVE main ("id", "documentId1", "assignmentId1", "documentId2", "assignmentId2", "created_at", "updated_at", "removed", "userId", "mainId") AS (
                    SELECT r1."id", r1."documentId1", r1."assignmentId1", r1."documentId2", r1."assignmentId2", r1."created_at", r1."updated_at", r1."removed", r1."userId", CAST (null AS bigint) AS mainId 
                        FROM "relations" AS r1 
                        WHERE (r1."documentId1" = :FltDocumentId OR r1."documentId2" = :FltDocumentId) AND r1."removed" IS NULL
                    UNION
                    SELECT r2."id", r2."documentId1", r2."assignmentId1", r2."documentId2", r2."assignmentId2", r2."created_at", r2."updated_at", r2."removed", r2."userId", CAST (main."id" AS bigint) AS mainId 
                        FROM "relations" AS r2 
                        JOIN main ON (r2."documentId1" = main."documentId2")  OR (r2."assignmentId1" = main."assignmentId2")
                        WHERE r2."removed" IS NULL
                ) 
                SELECT * FROM main ORDER BY "id";');
			$stm->bindValue("FltDocumentId", $documentId);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);

            if (count($result) > 0) {
            	$stmDoc = $pdo->prepare('SELECT "id", "description", "typeId", "creationDate", "orderNum", "created_at" FROM "GetDocumentById"(:FltDocumentId)');
            	$stmAssign = $pdo->prepare('SELECT "id", "typeId", "text", "created_at" FROM "GetAssignmentById"(:FltAssignmentId)');
            	$stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
            	$stmDocType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltDocTypeId)');
                $stmAssignType = $pdo->prepare('SELECT "id", "title" FROM "GetAssignmentTypeById"(:FltAssignTypeId)');
                foreach ($result as &$item) {
                	$stmAuthor->bindValue("FltAuthorId", $item['userId']);
                	$stmAuthor->execute();
                    $item['authorId'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                    $item['documentData1'] = null;
                    $item['documentData2'] = null;
                    $item['assignmentData1'] = null;
                    $item['assignmentData2'] = null;
                    if (isset($item['documentId1'])) {
                        $stmDoc->bindValue("FltDocumentId", $item['documentId1']);
                        $stmDoc->execute();
                        $item['documentData1'] = $stmDoc->fetch(PDO::FETCH_ASSOC);
                        $stmDocType->bindValue("FltDocTypeId", $item['documentData1']['typeId']);
                        $stmDocType->execute();
                        $item['documentData1']['type'] = $stmDocType->fetch(PDO::FETCH_ASSOC);
                    }
                    if (isset($item['documentId2'])) {
                        $stmDoc->bindValue("FltDocumentId", $item['documentId2']);
                        $stmDoc->execute();
                        $item['documentData2'] = $stmDoc->fetch(PDO::FETCH_ASSOC);
                        $stmDocType->bindValue("FltDocTypeId", $item['documentData2']['typeId']);
                        $stmDocType->execute();
                        $item['documentData2']['type'] = $stmDocType->fetch(PDO::FETCH_ASSOC);
                    }
                    if (isset($item['assignmentId1'])) {
                        $stmAssign->bindValue("FltAssignmentId", $item['assignmentId1']);
                        $stmAssign->execute();
                        $item['assignmentData1'] = $stmAssign->fetch(PDO::FETCH_ASSOC);
                        $stmAssignType->bindValue("FltAssignTypeId", $item['assignmentData1']['typeId']);
                        $stmAssignType->execute();
                        $item['assignmentData1']['type'] = $stmAssignType->fetch(PDO::FETCH_ASSOC);     
                    }
                    if (isset($item['assignmentId2'])) {
                        $stmAssign->bindValue("FltAssignmentId", $item['assignmentId2']);
                        $stmAssign->execute();
                        $item['assignmentData2'] = $stmAssign->fetch(PDO::FETCH_ASSOC);
                        $stmAssignType->bindValue("FltAssignTypeId", $item['assignmentData2']['typeId']);
                        $stmAssignType->execute();
                        $item['assignmentData2']['type'] = $stmAssignType->fetch(PDO::FETCH_ASSOC);
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

    public function listByAssignId($assignmentId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('WITH RECURSIVE main ("id", "documentId1", "assignmentId1", "documentId2", "assignmentId2", "created_at", "updated_at", "removed", "userId", "mainId") AS (
                    SELECT r1."id", r1."documentId1", r1."assignmentId1", r1."documentId2", r1."assignmentId2", r1."created_at", r1."updated_at", r1."removed", r1."userId", CAST (null AS bigint) AS mainId 
                        FROM "relations" AS r1 
                        WHERE (r1."assignmentId1" = :FltAssignmentId OR r1."assignmentId2" = :FltAssignmentId) AND r1."removed" IS NULL
                    UNION
                    SELECT r2."id", r2."documentId1", r2."assignmentId1", r2."documentId2", r2."assignmentId2", r2."created_at", r2."updated_at", r2."removed", r2."userId", CAST (main."id" AS bigint) AS mainId 
                        FROM "relations" AS r2 
                        JOIN main ON (r2."documentId1" = main."documentId2") OR (r2."assignmentId1" = main."assignmentId2")
                        WHERE r2."removed" IS NULL
                )
                SELECT * FROM main ORDER BY "id";');
			$stm->bindValue("FltAssignmentId", $assignmentId);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
            	$stmDoc = $pdo->prepare('SELECT "id", "description", "typeId", "creationDate", "orderNum", "created_at" FROM "GetDocumentById"(:FltDocumentId)');
            	$stmAssign = $pdo->prepare('SELECT "id", "typeId", "text", "created_at" FROM "GetAssignmentById"(:FltAssignmentId)');
            	$stmAuthor = $pdo->prepare('SELECT "id", "login", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAuthorId)');
            	$stmDocType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltDocTypeId)');
                $stmAssignType = $pdo->prepare('SELECT "id", "title" FROM "GetAssignmentTypeById"(:FltAssignTypeId)');
                foreach ($result as &$item) {
                	$stmAuthor->bindValue("FltAuthorId", $item['userId']);
                	$stmAuthor->execute();
                    $item['authorId'] = $stmAuthor->fetch(PDO::FETCH_ASSOC);
                    $item['documentData1'] = null;
                    $item['documentData2'] = null;
                    $item['assignmentData1'] = null;
                    $item['assignmentData2'] = null;
                    if (isset($item['documentId1'])) {
    	                $stmDoc->bindValue("FltDocumentId", $item['documentId1']);
		                $stmDoc->execute();
		                $item['documentData1'] = $stmDoc->fetch(PDO::FETCH_ASSOC);
		                $stmDocType->bindValue("FltDocTypeId", $item['documentData1']['typeId']);
                        $stmDocType->execute();
                        $item['documentData1']['type'] = $stmDocType->fetch(PDO::FETCH_ASSOC);
                    }
                    if (isset($item['documentId2'])) {
    	                $stmDoc->bindValue("FltDocumentId", $item['documentId2']);
		                $stmDoc->execute();
		                $item['documentData2'] = $stmDoc->fetch(PDO::FETCH_ASSOC);
		                $stmDocType->bindValue("FltDocTypeId", $item['documentData2']['typeId']);
                        $stmDocType->execute();
                        $item['documentData2']['type'] = $stmDocType->fetch(PDO::FETCH_ASSOC);
                    }
                    // echo "string";
                    if (isset($item['assignmentId1'])) {
    	                $stmAssign->bindValue("FltAssignmentId", $item['assignmentId1']);
		                $stmAssign->execute();
		                $item['assignmentData1'] = $stmAssign->fetch(PDO::FETCH_ASSOC);
		                $stmAssignType->bindValue("FltAssignTypeId", $item['assignmentData1']['typeId']);
                        $stmAssignType->execute();
                        $item['assignmentData1']['type'] = $stmAssignType->fetch(PDO::FETCH_ASSOC); 	
                    }
                    if (isset($item['assignmentId2'])) {
    	                $stmAssign->bindValue("FltAssignmentId", $item['assignmentId2']);
		                $stmAssign->execute();
		                $item['assignmentData2'] = $stmAssign->fetch(PDO::FETCH_ASSOC);
		                $stmAssignType->bindValue("FltAssignTypeId", $item['assignmentData2']['typeId']);
                        $stmAssignType->execute();
                        $item['assignmentData2']['type'] = $stmAssignType->fetch(PDO::FETCH_ASSOC);
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

    public function add(RelationArrayDto $data) 
    {
    	try {
    		$pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewRelation"(:FltDocumentId1, :FltDocumentId2, :FltAssignmentId1, :FltAssignmentId2, :FltUserId)');
            $result = [];
            foreach ($data->relations as $item) {
                $stm->bindValue("FltDocumentId1", $item->documentId1 ?? null);
                $stm->bindValue("FltDocumentId2", $item->documentId2 ?? null);
                $stm->bindValue("FltAssignmentId1", $item->assignmentId1 ?? null);
                $stm->bindValue("FltAssignmentId2", $item->assignmentId2 ?? null);
                $stm->bindValue("FltUserId", $item->userId);
                $stm->execute();
                $result[] = $stm->fetch(PDO::FETCH_ASSOC);
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
            $stm = $pdo->prepare('SELECT * FROM "RemoveRelation"(:FltId)');
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

    public function update(RelationDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdateDocument"(:FltId, :FltDocumentId1, :FltDocumentId2, :FltAssignmentId1, :FltAssignmentId2)');
            $stm->bindValue("FltId", $id);
            $stm->bindValue("FltDocumentId1", $documentId1);
            $stm->bindValue("FltDocumentId2", $documentId2);
            $stm->bindValue("FltAssignmentId1", $assignmentId1);
            $stm->bindValue("FltAssignmentId2", $assignmentId2);
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
