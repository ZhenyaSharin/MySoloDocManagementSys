<?php

namespace App\Http\Controllers\Api;

use App\BusinessLogic\AdminLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\BlogRequest;
use App\Http\Requests\Users\UserPasswordRequest;
use App\Http\Requests\Users\UserRoleRequest;
use App\Http\Requests\Users\UserRequest;

class AdminController extends Controller
{
    private $admin;

    public function __construct(AdminLogic $admin)
    {
        $this->admin = $admin;
    }

    public function getUsersList()
    {
        $result = $this->admin->getAllUsers();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addUser(UserPasswordRequest $request)
    {
        $data = $request->data();
        $result = $this->admin->addUser($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateUser(UserRequest $request)
    {
        $data = $request->data();
        $result = $this->admin->updateUser($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getBlogItems()
    {
        $result = $this->admin->getBlogItems();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addBlogItem(BlogRequest $request)
    {
        $data = $request->data();
        $result = $this->admin->addBlogItem($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateBlogItem(BlogRequest $request)
    {
        $data = $request->data();
        $result = $this->admin->updateBlogItem($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateUserPassword(UserPasswordRequest $request)
    {
        $data = $request->data();
        $result = $this->admin->updateUserPassword($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addAdmin(UserPasswordRequest $request)
    {
        $data = $request->data();
        $result = $this->admin->addAdmin($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getRolesList() 
    {
        $result = $this->admin->getRolesList();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addRole(UserRoleRequest $request)
    {
        $data = $request->data();
        $result = $this->admin->addRole($data);
        return response()->json(["error" => "0", "result" => $result]);
    }
}
