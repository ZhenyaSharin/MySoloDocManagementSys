<?php

namespace App\BusinessLogic;

use App\Dto\BlogDto\BlogDto;
use App\Dto\UserDto\UserDto;
use App\Dto\UserDto\UserPasswordDto;
use App\Dto\UserDto\UserRoleDto;
use App\Exceptions\BusinessLogicException;
use App\Exceptions\DatabaseException;
use App\Models\Repositories\Contracts\BlogRepositoryInterface;
use App\Models\Repositories\Contracts\UsersRepositoryInterface;
use Throwable;

class AdminLogic
{
    private $users;
    private $blog;

    public function __construct(UsersRepositoryInterface $users, BlogRepositoryInterface $blog)
    {
        $this->users = $users;
        $this->blog = $blog;
    }

    public function getAllUsers()
    {
        try {
            return $this->users->listAdmin();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function addUser(UserPasswordDto $data)
    {
        try {
            return $this->users->add($data);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateUser(UserDto $data)
    {
        try {
            if (isset($data->delete)) {
                if ($data->delete == 1) {
                    $result = $this->users->remove($data->id);
                } else if ($data->delete == 0) {
                    $result = $this->users->unblock($data->id);
                }
            } else {
                $result = $this->users->update($data);
            }
            return $result;
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getBlogItems()
    {
        try {
            return $this->blog->list();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function addBlogItem(BlogDto $data)
    {
        try {
            return $this->blog->add($data);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function updateBlogItem(BlogDto $data)
    {
        try {
            if (isset($data->delete)) {
                return $this->blog->remove($data->id);
            } else {
                return $this->blog->update($data);
            }
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }
    // продолжить в репозиториях БД

    public function updateUserPassword(UserPasswordDto $data)
    {
        try {
            return $this->users->updatePassword($data);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function addAdmin(UserPasswordDto $data)
    {
        try {
            return $this->users->addAdmin($data);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function getRolesList()
    {
        try {
            return $this->users->listRoles();
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }

    public function addRole(UserRoleDto $data)
    {
        try {
            return $this->users->addUserRole($data);
        } catch (Throwable $e) {
            if ($e instanceof DatabaseException) {
                throw new DatabaseException('DatabaseException');
            }
            throw new BusinessLogicException('BusinessLogicException: ' . $e->getMessage());
        }
    }
}
