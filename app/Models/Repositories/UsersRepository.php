<?php

namespace App\Models\Repositories;

use App\Dto\UserDto\UserDto;
use App\Dto\UserDto\UserPasswordDto;
use App\Dto\UserDto\UserRoleDto;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\UsersRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use PDO;

class UsersRepository implements UsersRepositoryInterface
{
    public function list()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllUsers"() WHERE "roleid" != 1 AND "removed" IS NULL ORDER BY "surname" ASC');
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

    public function listAdmin()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllUsers"() WHERE "roleid" != 1 ORDER BY "surname" ASC');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (isset($result)) {
                $stmUserDep = $pdo->prepare('SELECT * FROM "GetUserAndDepartmentByUserId"(:FltUserId) WHERE "removed" IS NULL');
                $stmDepartment = $pdo->prepare('SELECT * FROM "GetDepartmentById"(:FltId)');

                $stmUserRole = $pdo->prepare('SELECT * FROM "GetUserRolesByUserId"(:FltUserId)  WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmRole = $pdo->prepare('SELECT "id", "title", "slug" FROM "GetRoleById"(:FltRoleId)');

                $stmAdmin = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltAdminId)');
                foreach ($result as &$item) {
                    $stmUserDep->bindValue('FltUserId', $item['id']);
                    $stmUserDep->execute();
                    $userDep = $stmUserDep->fetchAll(PDO::FETCH_ASSOC);
                    $item['department'] = [];
                    if ($userDep) {
                        foreach ($userDep as $value) {
                            $stmDepartment->bindValue('FltId', $value['departmentId']);
                            $stmDepartment->execute();
                            $item['department'][] = $stmDepartment->fetch(PDO::FETCH_ASSOC);
                        }
                    }
                    $stmUserRole->bindValue("FltUserId", $item['id']);
                    $stmUserRole->execute();
                    $userRole = $stmUserRole->fetch(PDO::FETCH_ASSOC);
                    $item['role'] = false;
                    if ($userRole != false) {
                        $stmRole->bindValue("FltRoleId", $userRole['roleId']);
                        $stmRole->execute();
                        $item['role'] = $stmRole->fetch(PDO::FETCH_ASSOC);

                        $stmAdmin->bindValue("FltAdminId", $userRole['adminId']);
                        $stmAdmin->execute();
                        $item['role']['admin'] = $stmAdmin->fetch(PDO::FETCH_ASSOC);
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

    public function update(UserDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdateUser"(:FltId, :FltLogin, :FltSurname, :FltFirstname, :FltPatronymic, :FltEmail, :FltDepartment, :FltRoleId)');
            $stm->bindValue("FltId", $data->id);
            $stm->bindValue("FltLogin", $data->login);
            $stm->bindValue("FltSurname", $data->surname);
            $stm->bindValue("FltFirstname", $data->firstname);
            $stm->bindValue("FltPatronymic", $data->patronymic);
            $stm->bindValue("FltDepartment", null);
            $stm->bindValue("FltEmail", $data->email);
            $stm->bindValue("FltRoleId", $data->roleId);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmDepRemove = $pdo->prepare('SELECT * FROM "RemoveUserAndDepartmentByUserId"(:FltUserId) WHERE "removed" IS NULL');
                $stmDepRemove->bindValue("FltUserId", $data->id);
                $stmDepRemove->execute();
                $removeDep = $stmDepRemove->fetchAll(PDO::FETCH_ASSOC);
                $result['department'] = [];

                $stmDepartment = $pdo->prepare('SELECT * FROM "AddUserAndDepartment"(:FltUserId, :FltDepartmentId)');
                foreach ($data->department as $item) {
                    $stmDepartment->bindValue("FltUserId", $data->id);
                    $stmDepartment->bindValue("FltDepartmentId", $item['id']);
                    $stmDepartment->execute();
                    $result['department'][] = $stmDepartment->fetch(PDO::FETCH_ASSOC);
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

    public function remove(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "RemoveUserByUserId"(:FltId)');
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

    public function add(UserPasswordDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewUser"(:FltLogin, :FltSurname, :FltFirstname, :FltPatronymic, :FltEmail, :FltDepartment, :FltPassword, :FltRoleId)');
            $stm->bindValue("FltLogin", $data->login);
            $stm->bindValue("FltSurname", $data->surname);
            $stm->bindValue("FltFirstname", $data->firstname);
            $stm->bindValue("FltPatronymic", $data->patronymic);
            $stm->bindValue("FltEmail", $data->email);
            $stm->bindValue("FltDepartment", null);
            $stm->bindValue("FltPassword", $data->password);
            $stm->bindValue("FltRoleId", $data->roleId);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $result['department'] = [];

                $stmDepartment = $pdo->prepare('SELECT * FROM "AddUserAndDepartment"(:FltUserId, :FltDepartmentId)');
                foreach ($data->department as $item) {
                    $stmDepartment->bindValue("FltUserId", $result['id']);
                    $stmDepartment->bindValue("FltDepartmentId", $item['id']);
                    $stmDepartment->execute();

                    $result['department'][] = $stmDepartment->fetch(PDO::FETCH_ASSOC);
                }

                $stmSettArr = $pdo->prepare('SELECT * FROM "GetAllMailsettings"() WHERE "removed" IS NULL');
                $stmSettArr->execute();
                $settings = $stmSettArr->fetchAll(PDO::FETCH_ASSOC);
                $stmSetting = $pdo->prepare('SELECT * FROM "AddNewMailsettingUser"(:FltUserId, :FltSettingId, :FltStatus)');
                $result['settings'] = [];
                foreach ($settings as $item) {
                    $stmSetting->bindValue("FltUserId", $result['id']);
                    $stmSetting->bindValue("FltSettingId", $item['id']);
                    $stmSetting->bindValue("FltStatus", true);
                    $stmSetting->execute();
                    $result['settings'][] = $stmSetting->fetch(PDO::FETCH_ASSOC);
                }
                // доделать

                $stmRole = $pdo->prepare('SELECT * FROM "AddUserRole"(:FltUserId, :FltRoleId, :FltAdminId)');
                $stmRole->bindValue("FltUserId", $result['id']);
                $stmRole->bindValue("FltRoleId", $data->roleId);
                $stmRole->bindValue("FltAdminId", $data->adminId);
                // echo($result['id']);
                $stmRole->execute();
                $result['roleId'] = $stmRole->fetch(PDO::FETCH_ASSOC);
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

    public function unblock(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UnblockUserByUserId"(:FltId)');
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

    public function listBlog()
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

    public function getById(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetUserById"(:FltId)');
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

    public function getByIdWithInfo(int $id)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetUserById"(:FltId)');
            $stm->bindValue("FltId", $id);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if (isset($result)) {
                $stmUserDep = $pdo->prepare('SELECT * FROM "GetUserAndDepartmentByUserId"(:FltUserId) WHERE "removed" IS NULL');
                $stmDepartment = $pdo->prepare('SELECT "id", "title" FROM "GetDepartmentById"(:FltId)');
                $stmUserDep->bindValue('FltUserId', $result['id']);
                $stmUserDep->execute();
                $userDep = $stmUserDep->fetchAll(PDO::FETCH_ASSOC);
                if ($userDep) {
                    foreach ($userDep as &$item) {
                        $stmDepartment->bindValue('FltId', $item['departmentId']);
                        $stmDepartment->execute();
                        $depData = $stmDepartment->fetch(PDO::FETCH_ASSOC);
                        $item['departmentId'] = $depData['id'];
                        $item['title'] = $depData['title'];
                    }
                }
                $result['department'] = $userDep;

                $stmUserRoles = $pdo->prepare('SELECT "id", "userId", "roleId", "created_at" FROM "GetUserRolesByUserId"(:FltUserId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
                $stmUserRoles->bindValue("FltUserId", $id);
                $stmUserRoles->execute();
                $result['role'] = $stmUserRoles->fetch(PDO::FETCH_ASSOC);
                if ($result['role'] != false) {
                    $stmUserRole = $pdo->prepare('SELECT "id", "title", "slug" FROM "GetRoleById"(:FltRoleId)');
                    $stmUserRole->bindValue("FltRoleId", $result['role']['roleId']);
                    $stmUserRole->execute();
                    $role = $stmUserRole->fetch(PDO::FETCH_ASSOC);
                    $result['role']['roleTitle'] = $role['title'];
                    $result['role']['roleId'] = $role['id'];
                    $result['role']['slug'] = $role['slug'];
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

    public function updatePassword(UserPasswordDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdatePasswordByUserId"(:FltId, :FltPassword)');
            $stm->bindValue("FltId", $data->id);
            $stm->bindValue("FltPassword", $data->password);
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

    public function getByLogin(string $login)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetUserByLogin"(:FltLogin)');
            $stm->bindValue("FltLogin", $login);
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

    public function getByLoginWithInfo(string $login)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetUserByLogin"(:FltLogin)');
            $stm->bindValue("FltLogin", $login);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if (isset($result)) {
                $stmUserDep = $pdo->prepare('SELECT * FROM "GetUserAndDepartmentByUserId"(:FltUserId) WHERE "removed" IS NULL');
                $stmDepartment = $pdo->prepare('SELECT "id", "title" FROM "GetDepartmentById"(:FltId)');
                $stmUserDep->bindValue('FltUserId', $result['id']);
                $stmUserDep->execute();
                $userDep = $stmUserDep->fetchAll(PDO::FETCH_ASSOC);
                if ($userDep) {
                    foreach ($userDep as &$item) {
                        $stmDepartment->bindValue('FltId', $item['departmentId']);
                        $stmDepartment->execute();
                        $depData = $stmDepartment->fetch(PDO::FETCH_ASSOC);
                        $item['departmentId'] = $depData['id'];
                        $item['title'] = $depData['title'];
                    }
                }
                $result['department'] = $userDep;
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

    public function listDepartments()
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

    // Доделать документы и поручения
    public function listDepartmentAnalytics()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllDepartments"() WHERE "removed" IS NULL');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (isset($result)) {
                $stmUserDep = $pdo->prepare('SELECT * FROM "GetUserAndDepartmentByDepartmentId"(:FltDepartmentId) WHERE "removed" IS NULL');
                $stmUser = $pdo->prepare('SELECT "id", "surname", "firstname", "patronymic" FROM "GetUserById"(:FltUserId) WHERE "removed" IS NULL');
                $stmDocs = $pdo->prepare('SELECT "id", "description" FROM "GetDocsByUserId"(:FltUserId) WHERE "removed" IS NULL');
                $stmAssigns = $pdo->prepare('SELECT "id", "text" FROM "GetAssignmentsByAuthorId"(:FltAuthorId) WHERE "removed" IS NULL');
                foreach ($result as &$item) {
                    $stmUserDep->bindValue('FltDepartmentId', $item['id']);
                    $stmUserDep->execute();
                    $userDep = $stmUserDep->fetchAll(PDO::FETCH_ASSOC);
                    $item['users'] = [];
                    $item['docs'] = [];
                    $item['assigns'] = [];
                    foreach ($userDep as $value) {
                        $stmUser->bindValue('FltUserId', $value['userId']);
                        $stmUser->execute();
                        $user = $stmUser->fetch(PDO::FETCH_ASSOC);
                        if ($user != false) {
                            $item['users'][] = $user;
                        }

                        $stmDocs->bindValue('FltUserId', $value['userId']);
                        $stmDocs->execute();
                        $docs = $stmDocs->fetchAll(PDO::FETCH_ASSOC);
                        if ($docs != []) {
                            foreach ($docs as $doc) {
                                $item['docs'][] = $doc;
                            }
                        }

                        $stmAssigns->bindValue('FltAuthorId', $value['userId']);
                        $stmAssigns->execute();
                        $assigns = $stmAssigns->fetchAll(PDO::FETCH_ASSOC);
                        if ($assigns != []) {
                            foreach ($assigns as $assign) {
                                $item['assigns'][] = $assign;
                            }
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

    public function listAnalytics()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllUsers"() WHERE "roleid" != 1 ORDER BY "id" DESC');
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (isset($result)) {
                $stmDocs = $pdo->prepare('SELECT "id", "description" FROM "GetDocsByUserId"(:FltUserId) WHERE "removed" IS NULL');
                $stmAssigns = $pdo->prepare('SELECT "id", "text" FROM "GetAssignmentsByAuthorId"(:FltAuthorId) WHERE "removed" IS NULL');
                $item['docs'] = [];
                $item['assigns'] = [];
                foreach ($result as &$item) {
                    $stmDocs->bindValue('FltUserId', $item['id']);
                    $stmDocs->execute();
                    $docs = $stmDocs->fetchAll(PDO::FETCH_ASSOC);
                    if (isset($docs)) {
                        $item['docs'] = $docs;
                    }

                    $stmAssigns->bindValue('FltAuthorId', $item['id']);
                    $stmAssigns->execute();
                    $assigns = $stmAssigns->fetchAll(PDO::FETCH_ASSOC);
                    if (isset($assigns)) {
                        $item['assigns'] = $assigns;
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

    public function listMailSettings()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT "id", "title" FROM "GetAllMailsettings"() WHERE "removed" IS NULL ORDER BY "id" ASC');
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

    public function updateMailSetting(int $id, int $status)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "UpdateMailsettingUserWithStatus"(:FltId, :FltStatus)');
            $stm->bindValue('FltId', $id);
            $stm->bindValue('FltStatus', $status);
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

    public function addAllMailSettings(int $userId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewMailsettingUser"(:FltUserId, :FltSettingId, :FltStatus)');
            $stmSettArr = $pdo->prepare('SELECT * FROM "GetAllMailsettings"() WHERE "removed" IS NULL');
            $stmSettArr->execute();
            $settings = $stmSettArr->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            if (isset($result)) {
                foreach ($settings as $item) {
                    $stm->bindValue("FltUserId", $userId);
                    $stm->bindValue("FltSettingId", $item['id']);
                    $stm->bindValue("FltStatus", 1);
                    $stm->execute();
                    $result[] = $stm->fetch(PDO::FETCH_ASSOC);
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

    public function listMailSettingsByUserId(int $userId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stmSettArr = $pdo->prepare('SELECT * FROM "GetAllMailsettings"() WHERE "removed" IS NULL');
            $stm = $pdo->prepare('SELECT * FROM "GetMailsettingsUsersByUserIdAndSettingId"(:FltUserId, :FltSettingId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
            $stmSettArr->execute();
            $result = $stmSettArr->fetchAll(PDO::FETCH_ASSOC);
            if (isset($result)) {
                foreach ($result as &$item) {
                    $stm->bindValue('FltUserId', $userId);
                    $stm->bindValue('FltSettingId', $item['id']);
                    $stm->execute();
                    $item['user'] = $stm->fetch(PDO::FETCH_ASSOC);
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

    public function getMailsettingByUserIdAndSettingId(int $userId, int $settingId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetMailsettingsUsersByUserIdAndSettingId"(:FltUserId, :FltSettingId) WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
            $stm->bindValue('FltUserId', $userId);
            $stm->bindValue('FltSettingId', $settingId);
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

    public function addNewMailSettingUser(int $userId, int $settingId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewMailsettingUser"(:FltUserId, :FltSettingId, :FltStatus)');
            $stm->bindValue('FltUserId', $userId);
            $stm->bindValue('FltSettingId', $settingId);
            $stm->bindValue('FltStatus', 1);
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

    public function getByRoleId(int $roleId)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetUsersByRoleId"(:FltRoleId)');
            $stm->bindValue("FltRoleId", $roleId);
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

    public function addAdmin(UserPasswordDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddNewUser"(:FltLogin, :FltSurname, :FltFirstname, :FltPatronymic, :FltEmail, :FltDepartment, :FltPassword, :FltRoleId)');
            $stm->bindValue("FltLogin", $data->login);
            $stm->bindValue("FltSurname", $data->surname);
            $stm->bindValue("FltFirstname", $data->firstname);
            $stm->bindValue("FltPatronymic", $data->patronymic);
            $stm->bindValue("FltEmail", $data->email);
            $stm->bindValue("FltDepartment", null);
            $stm->bindValue("FltPassword", $data->password);
            $stm->bindValue("FltRoleId", $data->roleId);
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

    public function listRoles()
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetAllRoles"() WHERE "removed" IS NULL AND "slug" != \'ADMIN\'');
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

    public function addUserRole(UserRoleDto $data)
    {

        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "AddUserRole"(:FltUserId, :FltRoleId, :FltAdminId)');
            $stm->bindValue("FltUserId", $data->userId);
            $stm->bindValue("FltRoleId", $data->roleId);
            $stm->bindValue("FltAdminId", $data->adminId);
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


    public function userRoleById(UserRoleDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetUserRoleById"(:FltId)');
            $stm->bindValue("FltId", $data->id);
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

    public function getUserRoleByUserId(UserRoleDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetUserRolesByUserId"(:FltUserId)  WHERE "removed" IS NULL ORDER BY "created_at" DESC LIMIT 1');
            $stm->bindValue("FltUserId", $data->userId);
            $stm->execute();
            $result = $stm->fetch(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmRole = $pdo->prepare('SELECT "id", "title", "slug" FROM "GetRoleById"(:FltRoleId)');

                $stmAdmin = $pdo->prepare('SELECT * FROM "GetUserById"(:FltAdminId)');
                $stmRole->bindValue("FltRoleId", $result['roleId']);
                $stmRole->execute();
                $result['role'] = $stmRole->fetch(PDO::FETCH_ASSOC);

                // $stmAdmin->bindValue("FltAdminId", $result['adminId']);
                // $stmAdmin->execute();
                // $result['admin'] = $stmAdmin->fetch(PDO::FETCH_ASSOC);
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

    public function listUserRoleByUserId(UserRoleDto $data)
    {
        try {
            $pdo = DB::connection()->getPDO();
            $pdo->beginTransaction();
            $stm = $pdo->prepare('SELECT * FROM "GetUserRolesByUserId"(:FltUserId)  WHERE "removed" IS NULL ORDER BY "created_at" DESC');
            $stm->bindValue("FltUserId", $data->userId);
            $stm->execute();
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if ($result != false) {
                $stmRole = $pdo->prepare('SELECT * FROM "GetRoleById"(:FltRoleId)');

                $stmAdmin = $pdo->prepare('SELECT * FROM "GetUserById"(:FltAdminId)');
                foreach ($result as &$item) {
                    $stmRole->bindValue("FltRoleId", $item['roleId']);
                    $stmRole->execute();
                    $item['role'] = $stmRole->fetch(PDO::FETCH_ASSOC);

                    $stmAdmin->bindValue("FltAdminId", $item['adminId']);
                    $stmAdmin->execute();
                    $item['admin'] = $stmAdmin->fetch(PDO::FETCH_ASSOC);
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
