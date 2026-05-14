<?php

namespace App\BusinessLogic;

use App\Dto\UserDto\MailsettingsUserDto;
use App\Dto\UserDto\UserDto;
use App\Dto\UserDto\UserRoleDto;
use App\Exceptions\BusinessLogicException;
use App\Exceptions\DatabaseException;
use App\Exceptions\MailSettingException;
use App\Mail\SystemMail;
use App\Models\Repositories\Contracts\UsersRepositoryInterface;
use Illuminate\Support\Facades\Mail;
use Throwable;

class UsersLogic
{
    private $users;

    public function __construct(UsersRepositoryInterface $users)
    {
        $this->users = $users;
    }

    public function getUsersList(UserDto $data)
    {
        try {
            if ($data->id == 3) {
                return $this->users->listAdmin();
            } else {
                return $this->users->list();
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }
    // getById(int $id)

    public function getUserBySmth(UserDto $data)
    {
        try {
            if ($data->id != null) {
                if ($data->info != null) {
                    return $this->users->getByIdWithInfo($data->id);
                } else {
                    return $this->users->getById($data->id);
                }
            } else if ($data->login != null) {
                if ($data->info != null) {
                    return $this->users->getByLoginWithInfo($data->login);
                } else {
                    return $this->users->getByLogin($data->login);
                }
            } else if ($data->roleId != null) {
                return $this->users->getByRoleId($data->roleId);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getDepartmentsList()
    {
        try {
            return $this->users->listDepartments();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getDepartmentAnalyticsList()
    {
        try {
            return $this->users->listDepartmentAnalytics();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getUsersAnalyticsList()
    {
        try {
            return $this->users->listAnalytics();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getMailSettingsList(MailsettingsUserDto $data)
    {
        try {
            if ($data->userId != null) {
                return $this->users->listMailSettingsByUserId($data->userId);
            }
            return $this->users->listMailSettings();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateMailSettingStatus(MailsettingsUserDto $data)
    {
        try {
            return $this->users->updateMailSetting($data->id, $data->status);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function addMailSettingsByUserId(UserDto $data)
    {
        try {
            return $this->users->addAllMailSettings($data->id);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function checkMailSetting(int $userId, int $settingId)
    {
        try {
            $setting = $this->users->getMailsettingByUserIdAndSettingId($userId, $settingId);
            if ($setting != false) {
                if ($setting['status'] == 'true') {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new MailSettingException('MailSettingException: ' . $e->getMessage());
        }
    }

    public function addMailSettingUser(MailsettingsUserDto $data)
    {
        try {
            return $this->users->addNewMailSettingUser($data->userId, $data->settingId);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function sendMail(array $data, int $mailType, string $email, int $userId)
    {
        try {
            if ($this->checkMailSetting($userId, $mailType)) {
                Mail::to($email)->send(new SystemMail($data));
                return true;
            }
            return false;
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function checkUserRole(UserRoleDto $data)
    {
        try {
            if (isset($data->last)) {
                return $this->users->getUserRoleByUserId($data);
            }
            return $this->users->listUserRoleByUserId($data);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }
}
