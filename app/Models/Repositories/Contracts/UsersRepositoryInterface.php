<?php

namespace App\Models\Repositories\Contracts;

use App\Dto\UserDto\UserDto;
use App\Dto\UserDto\UserPasswordDto;
use App\Dto\UserDto\UserRoleDto;

interface UsersRepositoryInterface
{
    function list();

    public function update(UserDto $data);

    public function remove(int $id);

    public function add(UserPasswordDto $data);

    public function listAdmin();

    public function getById(int $id);

    public function getByIdWithInfo(int $id);

    public function updatePassword(UserPasswordDto $data);

    public function getByLogin(string $login);

    public function getByLoginWithInfo(string $login);

    public function listDepartments();

    public function listDepartmentAnalytics();

    public function listAnalytics();

    public function listMailSettings();

    public function updateMailSetting(int $id, int $status);

    public function addAllMailSettings(int $userId);

    public function listMailSettingsByUserId(int $userId);

    public function getMailsettingByUserIdAndSettingId(int $userId, int $settingId);

    public function addNewMailSettingUser(int $userId, int $settingId);

    public function getByRoleId(int $roleId);
    
    public function addAdmin(UserPasswordDto $data);

    public function listRoles();

    public function addUserRole(UserRoleDto $data);

    public function userRoleById(UserRoleDto $data);

    public function getUserRoleByUserId(UserRoleDto $data);

    public function listUserRoleByUserId(UserRoleDto $data);
}
