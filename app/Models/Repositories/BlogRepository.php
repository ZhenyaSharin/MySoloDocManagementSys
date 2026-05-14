<?php

namespace App\Models\Repositories;

use App\Dto\BlogDto\BlogDto;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\BlogRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use PDO;

class BlogRepository implements BlogRepositoryInterface
{
    public function list()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetBlog"() WHERE "removed" IS NULL ORDER BY "created_at" DESC');
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

    public function add(BlogDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewBlogItem"(:FltTitle, :FltText)');
            $stm->bindValue("FltTitle", $data->title);
            $stm->bindValue("FltText", $data->text ?? null);
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

    public function update(BlogDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdateBlogItem"(:FltId, :FltTitle, :FltText)');
            $stm->bindValue("FltId", $data->id);
            $stm->bindValue("FltTitle", $data->title);
            $stm->bindValue("FltText", $data->text ?? null);
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

    public function remove(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RemoveBlogItem"(:FltId)');
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
}
