<?php

namespace App\Models\Repositories;

use App\Dto\FileDto\FileAdditionDto;
use App\Dto\FileDto\FileDto;
use App\Dto\FileDto\FileUploadDto;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\FilesRepositoryInterface;
use Illuminate\Support\Facades\DB;
use PDO;
use Throwable;

class FilesRepository implements FilesRepositoryInterface
{
    public function addDocFileAddition(FileAdditionDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
            $stmAdd = $pdo->prepare('SELECT * FROM "AddFileAndAddition"(:FltFileId, :FltDocumentId, :FltAssignmentId, :FltFeedbackId, :FltBlogId, :FltAgreementAndUserId)');
            $res = [];
            foreach ($data->files as $item) {
                $stm->bindValue("FltFile", $item->fileLink);
                $stm->bindValue("FltFormat", $item->format);
                $stm->bindValue("FltType", 1);
                $stm->bindValue("FltComment", $item->comment);
                $stm->execute();
                $file = $stm->fetch(PDO::FETCH_ASSOC);

                if (count($file) > 0) {
                    $stmAdd->bindValue("FltFileId", $file['id']);
                    $stmAdd->bindValue("FltDocumentId", $data->documentId);
                    $stmAdd->bindValue("FltAssignmentId", null);
                    $stmAdd->bindValue("FltFeedbackId", null);
                    $stmAdd->bindValue("FltBlogId", null);
                    $stmAdd->bindValue("FltAgreementAndUserId", null);
                    $stmAdd->execute();
                    $res['file'] = $file;
                    $result[] = $res;
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

    public function addAssignFileAddition(FileAdditionDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
            $stmAdd = $pdo->prepare('SELECT * FROM "AddFileAndAddition"(:FltFileId, :FltDocumentId, :FltAssignmentId, :FltFeedbackId, :FltBlogId, :FltAgreementAndUserId)');
            $res = [];
            foreach ($data->files as $item) {
                $stm->bindValue("FltFile", $item->fileLink);
                $stm->bindValue("FltFormat", $item->format);
                $stm->bindValue("FltType", 1);
                $stm->bindValue("FltComment", $item->comment);
                $stm->execute();
                $file = $stm->fetch(PDO::FETCH_ASSOC);

                if (count($file) > 0) {
                    $stmAdd->bindValue("FltFileId", $file['id']);
                    $stmAdd->bindValue("FltDocumentId", null);
                    $stmAdd->bindValue("FltAssignmentId", $data->assignmentId);
                    $stmAdd->bindValue("FltFeedbackId", null);
                    $stmAdd->bindValue("FltBlogId", null);
                    $stmAdd->bindValue("FltAgreementAndUserId", null);
                    $stmAdd->execute();
                    $res = $stmAdd = $stmAdd->fetch(PDO::FETCH_ASSOC);
                    $res['file'] = $file;
                    $result[] = $res;
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

    public function addFeedbackFileAddition(FileAdditionDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddFileAndAddition"(:FltFileId, :FltDocumentId, :FltAssignmentId, :FltFeedbackId, :FltBlogId, :FltAgreementAndUserId)');
            $stm->bindValue("FltFileId", $data->fileId);
            $stm->bindValue("FltDocumentId", null);
            $stm->bindValue("FltAssignmentId", null);
            $stm->bindValue("FltFeedbackId", $data->feedbackId);
            $stm->bindValue("FltBlogId", null);
            $stm->bindValue("FltAgreementAndUserId", null);
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

    public function remove(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RemoveFileAdditionById"(:FltId)');
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

    public function removeByDocumentId(int $documentId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RemoveFileAdditionByDocumentId"(:FltDocumentId)');
            $stm->bindValue("FltDocumentId", $documentId);
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

    public function removeByAssignmentId(int $assignmentId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RemoveFileAdditionByAssignmentId"(:FltAssignmentId)');
            $stm->bindValue("FltAssignmentId", $assignmentId);
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

    public function updateFileComment(FileDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdateFileComment"(:FltId, :FltComment)');
            $stm->bindValue("FltId", $data->id);
            $stm->bindValue("FltComment", $data->comment);
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

    public function updateFile(FileUploadDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewFile"(:FltFile, :FltFormat, :FltType, :FltComment)');
            $stm->bindValue("FltFile", $data->fileLink);
            $stm->bindValue("FltFormat", $data->fileFormat);
            $stm->bindValue("FltType", 1);
            $stm->bindValue("FltComment", null);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmFileDoc = $pdo->prepare('SELECT * FROM "AddDocumentAndFile"(:FltDocumentId, :FltFileId)');
                $stmFileDoc->bindValue("FltDocumentId", $data->documentId);
                $stmFileDoc->bindValue("FltFileId", $result['id']);
                $stmFileDoc->execute();
                $fileDoc = $stmFileDoc->fetch(PDO::FETCH_ASSOC);
                $result['fileDocId'] = $fileDoc['id'];
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
