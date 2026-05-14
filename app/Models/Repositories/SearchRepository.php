<?php

namespace App\Models\Repositories;

use App\Dto\SearchDto\SearchDto;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\SearchRepositoryInterface;
use Illuminate\Support\Facades\DB;
use PDO;

class SearchRepository implements SearchRepositoryInterface
{

    public function search(SearchDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $res = [];

            $res['documents'] = [];
            $res['assignments'] = [];

            if ($data->docStatuses != null) {
                $docStatuses = $this->createIn($data->docStatuses);
            }

            if ($data->docTypes != null) {
                $docTypes = $this->createIn($data->docTypes);
            }

            if ($data->assignStatuses != null) {
                $assignStatuses = $this->createIn($data->assignStatuses);
            }

            if ($data->assignTypes != null) {
                $assignTypes = $this->createIn($data->assignTypes);
            }

            $queryDoc = 'SELECT "d"."id", "d"."description", "d"."authorId", "d"."typeId", "d"."creationDate", "d"."orderNum" FROM "documents" AS "d" LEFT JOIN "documents_and_docstatuses" AS "ds" ON "ds"."documentId" = "d"."id"';

            $queryAssign = 'SELECT "a"."id", "a"."description", "a"."text", "a"."typeId" FROM "assignments" AS "a" LEFT JOIN "assignments_and_assignmentstatuses" AS "as" ON "as"."assignmentId" = "a"."id"';

            $users = '';
            if ($data->users != []) {
                $users = $this->createIn($data->users);

                $queryDoc .= ' LEFT JOIN "agreements" AS "agr" ON "agr"."documentId" = "d"."id" LEFT JOIN "agreements_and_users" AS "agru" ON "agru"."agreementId" = "agr"."id" ';

                $queryAssign .= ' LEFT JOIN "controls" AS "ct" ON "ct"."assignmentId" = "a"."id" ';
            }

            $queryDoc .= ' WHERE "d"."removed" IS NULL ';

            $queryAssign .= ' WHERE "a"."removed" IS NULL ';

            if ($data->docStatuses != null) {
                $queryDoc .= 'AND "ds"."docstatusId" IN (' . $docStatuses . ')';
            }

            if ($data->docTypes != null) {
                $queryDoc .= ' AND "d"."typeId" IN (' . $docTypes . ')';
            }

            if ($data->assignStatuses != null) {
                $queryAssign .= 'AND "as"."assignmentstatusId" IN (' . $assignStatuses . ')';
            }

            if ($data->assignTypes != null) {
                $queryAssign .= ' AND "a"."typeId" IN (' . $assignTypes . ')';
            }

            if ($data->users != []) {
                $queryDoc .= ' AND (("agr"."agreed_at" IS NOT NULL AND "agru"."userId" IN (' . $users . ')) OR "d"."authorId" IN (' . $users . '))';
                $queryAssign .= ' AND (("a"."authorId" IN (' . $users . ') OR "a"."executorId" IN (' . $users . ')) OR "ct"."userId" IN(' . $users . '))';
                // echo($users);
            }

            if ($data->period != null) {
                if ($data->period[0] != null) {
                    $queryDoc .= ' AND "d"."created_at" >= (:FltPeriodStart)';
                    $queryAssign .= ' AND "a"."created_at" >= (:FltPeriodStart)';
                }
                if ($data->period[1] != null) {
                    $queryDoc .= ' AND "d"."created_at" <= (:FltPeriodEnd)';
                    $queryAssign .= ' AND "a"."created_at" <= (:FltPeriodEnd)';
                }
            }

            if ($data->docDate != null) {
                if ($data->docDate == 0) {
                    $queryDoc .= ' AND "d"."creationDate" IS NULL';
                } else {
                    if ($data->docDate[0] != null) {
                        $queryDoc .= ' AND "d"."creationDate" >= (:FltDocDateStart)';
                        // $queryAssign .= ' AND "a"."creationDate" >= (:FltDocDateStart)';
                    }
                    if ($data->docDate[1] != null) {
                        $queryDoc .= ' AND "d"."creationDate" <= (:FltDocDateEnd)';
                        // $queryAssign .= ' AND "a"."creationDate" <= (:FltDocDateEnd)';
                    }
                }
            }

            if ($data->words != null) {
                $queryDoc .= ' AND LOWER("d"."description") SIMILAR TO LOWER(:FltString)';
                $queryAssign .= ' AND (LOWER("a"."text") SIMILAR TO LOWER(:FltString) OR LOWER("a"."description") SIMILAR TO LOWER(:FltString))';
            }

            if ($data->orderNum != null) {
                $queryDoc .= ' AND LOWER("d"."orderNum") SIMILAR TO LOWER(:FltOrderNum)';
            }

            $queryDoc .= ' GROUP BY "d"."id", "d"."description", "d"."authorId", "d"."typeId", "d"."creationDate"';

            $queryAssign .= ' GROUP BY "a"."id", "a"."description", "a"."text", "a"."typeId"';

            // echo($queryDoc);
            // echo "<br>";
            // echo($queryAssign);

            $stmDoc = $pdo->prepare($queryDoc);
            $stmAssign = $pdo->prepare($queryAssign);
            if ($data->words != null) {
                $stmDoc->bindValue("FltString", "%" . $data->words . "%");
                $stmAssign->bindValue("FltString", "%" . $data->words . "%");
            }

            $orderNum = '';
            foreach (str_split(preg_replace("/[\/\&%#\$-]/", '', stripslashes($data->orderNum))) as $item) {
                $orderNum .= $item.'%';
            }

            if ($data->orderNum != null) {
                $stmDoc->bindValue("FltOrderNum", $orderNum);
            }


            if ($data->period != null) {
                if ($data->period[0] != null) {
                    $stmDoc->bindValue("FltPeriodStart", $data->period[0]);
                    $stmAssign->bindValue("FltPeriodStart", $data->period[0]);
                }
                if ($data->period[1] != null) {
                    $stmDoc->bindValue("FltPeriodEnd", $data->period[1]);
                    $stmAssign->bindValue("FltPeriodEnd", $data->period[1]);
                }
            }

            if ($data->docDate != null) {
                if ($data->docDate[0] != null) {
                    $stmDoc->bindValue("FltDocDateStart", $data->docDate[0]);
                    // $stmAssign->bindValue("FltDocDateStart", $data->docDate[0]);
                }
                if ($data->docDate[1] != null) {
                    $stmDoc->bindValue("FltDocDateEnd", $data->docDate[1]);
                    // $stmAssign->bindValue("FltDocDateEnd", $data->docDate[1]);
                }
            }

            // echo($queryDoc);

            $stmDoc->execute();
            $stmAssign->execute();
            $res['documents'] = $stmDoc->fetchAll(PDO::FETCH_ASSOC);

            $res['assignments'] = $stmAssign->fetchAll(PDO::FETCH_ASSOC);

            if (isset($res['documents'])) {
                $stmDocType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                foreach ($res['documents'] as &$item) {
                    $stmDocType->bindValue("FltId", $item["typeId"]);
                    $stmDocType->execute();
                    $item['type'] = $stmDocType->fetch(PDO::FETCH_ASSOC);
                }
            }

            if (isset($res['assignments'])) {
                $stmAssignType = $pdo->prepare('SELECT "id",
                "title" FROM "GetAssignmentTypeById"(:FltId)');
                $stmAssignDeadline = $pdo->prepare('SELECT * FROM "GetAssignmentDeadlinesByAssignmentId"(:FltAssignmentId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmAssignStatus = $pdo->prepare('SELECT * FROM "GetAssignmentAndAssigmnentStatusesByAssignmentId"(:FltAssignmentId) ORDER BY "created_at" DESC LIMIT 1');
                $stmAssignStatusData = $pdo->prepare('SELECT * FROM "GetStatusById"(:FltId)');
                foreach ($res['assignments'] as &$item) {
                    $stmAssignType->bindValue("FltId", $item["typeId"]);
                    $stmAssignType->execute();
                    $item['type'] = $stmAssignType->fetch(PDO::FETCH_ASSOC);

                    $stmAssignDeadline->bindValue("FltAssignmentId", $item["id"]);
                    $stmAssignDeadline->execute();
                    $item['deadline'] = $stmAssignDeadline->fetchAll(PDO::FETCH_ASSOC);

                    $stmAssignStatus->bindValue("FltAssignmentId", $item['id']);
                    $stmAssignStatus->execute();
                    $alias = $stmAssignStatus->fetch(PDO::FETCH_ASSOC);
                    if ($alias != false) {
                        $stmAssignStatusData->bindValue("FltId", $alias['assignmentstatusId']);
                        $stmAssignStatusData->execute();
                        // echo 'wqdqwd';
                        $item['status'] = $stmAssignStatusData->fetch(PDO::FETCH_ASSOC);
                    }
                }
            }
            $result = $res;
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    public function searchAdditionalUsers(SearchDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $res = [];

            $res['documents'] = [];
            $res['assignments'] = [];

            if ($data->docStatuses != null) {
                $docStatuses = $this->createIn($data->docStatuses);
            }

            if ($data->docTypes != null) {
                $docTypes = $this->createIn($data->docTypes);
            }

            if ($data->assignStatuses != null) {
                $assignStatuses = $this->createIn($data->assignStatuses);
            }

            if ($data->assignTypes != null) {
                $assignTypes = $this->createIn($data->assignTypes);
            }

            $queryDoc = 'SELECT "d"."id", "d"."description", "d"."authorId", "d"."typeId", "d"."creationDate", "d"."orderNum" FROM "documents" AS "d" LEFT JOIN "documents_and_docstatuses" AS "ds" ON "ds"."documentId" = "d"."id"';

            $queryAssign = 'SELECT "a"."id", "a"."description", "a"."text", "a"."typeId" FROM "assignments" AS "a" LEFT JOIN "assignments_and_assignmentstatuses" AS "as" ON "as"."assignmentId" = "a"."id"';

            // $users = '';
            $docAuthor = '';
            $assignAuthor = '';
            $assignExecutor = '';

            // if ($data->users != []) {
            //     foreach ($data->users as $item) {
            //         $users .= $item . ',';
            //     }
            //     $users = substr($users, 0, -1);

            // $queryDoc .= ' LEFT JOIN "agreements" AS "agr" ON "agr"."documentId" = "d"."id" LEFT JOIN "agreements_and_users" AS "agru" ON "agru"."agreementId" = "agr"."id" ';

            // $queryAssign .= ' LEFT JOIN "controls" AS "ct" ON "ct"."assignmentId" = "a"."id" ';
            // }

            $queryDoc .= ' WHERE "d"."removed" IS NULL ';

            $queryAssign .= ' WHERE "a"."removed" IS NULL ';

            if ($data->docStatuses != null) {
                $queryDoc .= 'AND "ds"."docstatusId" IN (' . $docStatuses . ')';
            }

            if ($data->docTypes != null) {
                $queryDoc .= ' AND "d"."typeId" IN (' . $docTypes . ')';
            }

            if ($data->assignStatuses != null) {
                $queryAssign .= 'AND "as"."assignmentstatusId" IN (' . $assignStatuses . ')';
            }

            if ($data->assignTypes != null) {
                $queryAssign .= ' AND "a"."typeId" IN (' . $assignTypes . ')';
            }

            // if ($data->users != []) {
            //     $queryDoc .= ' AND (("agr"."agreed_at" IS NOT NULL AND "agru"."userId" IN (' . $users . ')) OR "d"."authorId" IN (' . $users . '))';
            //     $queryAssign .= ' AND (("a"."authorId" IN (' . $users . ') OR "a"."executorId" IN (' . $users . ')) OR "ct"."userId" IN(' . $users . '))';
            // }

            $docAuthor = '';
            $assignAuthor = '';
            $assignExecutor = '';

            if ($data->assignAuthor != []) {
                $assignAuthor = $this->createIn($data->assignAuthor);
            }

            if ($data->assignExecutor != []) {
                $assignExecutor = $this->createIn($data->assignExecutor);
            }

//
            if ($data->docAuthor != []) {
                $docAuthor = $this->createIn($data->docAuthor);
                $queryDoc .= ' AND "d"."authorId" IN (' . $docAuthor . ')';
            }

            if (($data->assignAuthor != []) && ($data->assignExecutor != [])) {
                // $assignAuthor
                // $assignExecutor
                $queryAssign .= ' AND (("a"."authorId" IN (' . $assignAuthor . ') AND "a"."executorId" IN (' . $assignExecutor . ')))';
            } else {
                if ($data->assignAuthor != []) {
                    $queryAssign .= ' AND "a"."authorId" IN (' . $assignAuthor . ')';
                }
                if ($data->assignExecutor != []) {
                    $queryAssign .= ' AND "a"."executorId" IN (' . $assignExecutor . ')';
                }
            }

//
            if ($data->period != null) {
                if ($data->period[0] != null) {
                    $queryDoc .= ' AND "d"."created_at" >= (:FltPeriodStart)';
                    $queryAssign .= ' AND "a"."created_at" >= (:FltPeriodStart)';
                }
                if ($data->period[1] != null) {
                    $queryDoc .= ' AND "d"."created_at" <= (:FltPeriodEnd)';
                    $queryAssign .= ' AND "a"."created_at" <= (:FltPeriodEnd)';
                }
            }

            if ($data->docDate != null) {
                if ($data->docDate == 0) {
                    $queryDoc .= ' AND "d"."creationDate" IS NULL';
                } else {
                    if ($data->docDate[0] != null) {
                        $queryDoc .= ' AND "d"."creationDate" >= (:FltDocDateStart)';
                        // $queryAssign .= ' AND "a"."creationDate" >= (:FltDocDateStart)';
                    }
                    if ($data->docDate[1] != null) {
                        $queryDoc .= ' AND "d"."creationDate" <= (:FltDocDateEnd)';
                        // $queryAssign .= ' AND "a"."creationDate" <= (:FltDocDateEnd)';
                    }
                }
            }

            if ($data->words != null) {
                $queryDoc .= ' AND LOWER("d"."description") SIMILAR TO LOWER(:FltString)';
                $queryAssign .= ' AND (LOWER("a"."text") SIMILAR TO LOWER(:FltString) OR LOWER("a"."description") SIMILAR TO LOWER(:FltString))';
            }

            if ($data->orderNum != null) {
                $queryDoc .= ' AND LOWER("d"."orderNum") SIMILAR TO LOWER(:FltOrderNum)';
            }

            $queryDoc .= ' GROUP BY "d"."id", "d"."description", "d"."authorId", "d"."typeId", "d"."creationDate"';

            $queryAssign .= ' GROUP BY "a"."id", "a"."description", "a"."text", "a"."typeId"';

            // echo($queryDoc);
            // echo "<br>";
            // echo($queryAssign);

            $stmDoc = $pdo->prepare($queryDoc);
            $stmAssign = $pdo->prepare($queryAssign);
            if ($data->words != null) {
                $stmDoc->bindValue("FltString", "%" . $data->words . "%");
                $stmAssign->bindValue("FltString", "%" . $data->words . "%");
            }

            $orderNum = '';
            foreach (str_split(preg_replace("/[\/\&%#\$-]/", '', stripslashes($data->orderNum))) as $item) {
                $orderNum .= $item.'%';
            }

            if ($data->orderNum != null) {
                $stmDoc->bindValue("FltOrderNum", $orderNum);
            }

            if ($data->period != null) {
                if ($data->period[0] != null) {
                    $stmDoc->bindValue("FltPeriodStart", $data->period[0]);
                    $stmAssign->bindValue("FltPeriodStart", $data->period[0]);
                }
                if ($data->period[1] != null) {
                    $stmDoc->bindValue("FltPeriodEnd", $data->period[1]);
                    $stmAssign->bindValue("FltPeriodEnd", $data->period[1]);
                }
            }

            if ($data->docDate != null) {
                if ($data->docDate[0] != null) {
                    $stmDoc->bindValue("FltDocDateStart", $data->docDate[0]);
                    // $stmAssign->bindValue("FltDocDateStart", $data->docDate[0]);
                }
                if ($data->docDate[1] != null) {
                    $stmDoc->bindValue("FltDocDateEnd", $data->docDate[1]);
                    // $stmAssign->bindValue("FltDocDateEnd", $data->docDate[1]);
                }
            }

            $stmDoc->execute();
            $stmAssign->execute();
            $res['documents'] = $stmDoc->fetchAll(PDO::FETCH_ASSOC);

            $res['assignments'] = $stmAssign->fetchAll(PDO::FETCH_ASSOC);

            if (isset($res['documents'])) {
                $stmDocType = $pdo->prepare('SELECT "id",
                "title" FROM "GetDocTypeById"(:FltId)');
                foreach ($res['documents'] as &$item) {
                    $stmDocType->bindValue("FltId", $item["typeId"]);
                    $stmDocType->execute();
                    $item['type'] = $stmDocType->fetch(PDO::FETCH_ASSOC);
                }
            }

            if (isset($res['assignments'])) {
                $stmAssignType = $pdo->prepare('SELECT "id",
                "title" FROM "GetAssignmentTypeById"(:FltId)');
                foreach ($res['assignments'] as &$item) {
                    $stmAssignType->bindValue("FltId", $item["typeId"]);
                    $stmAssignType->execute();
                    $item['type'] = $stmAssignType->fetch(PDO::FETCH_ASSOC);
                }
            }
            $result = $res;
            $pdo->commit();
            return $result;
        } catch (\PDOException | Throwable $e) {
            if ($pdo && $pdo->inTransaction()) {
                $pdo->rollback();
            }
            throw new DatabaseException($e->getMessage());
        }
    }

    private function createIn(array $arr)
    {
        $result = '';

        if ($arr != null) {
            foreach ($arr as $item) {
                $result .= $item . ',';
                $result = substr($result, 0, -1);
            }
        }
        return $result;
    }
}
