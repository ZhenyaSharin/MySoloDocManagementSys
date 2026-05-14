<?php

namespace App\Http\Controllers\Api;

use App\BusinessLogic\UsersLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\MailsettingsUserRequest;
use App\Http\Requests\Users\UserRequest;
use App\Http\Requests\Users\UserRoleRequest;

class UsersController extends Controller
{
    private $users;

    public function __construct(UsersLogic $users)
    {
        $this->users = $users;
    }

    public function getUsersList(UserRequest $request)
    {
        $data = $request->data();
        $result = $this->users->getUsersList($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getUserBySmth(UserRequest $request)
    {
        $data = $request->data();
        $result = $this->users->getUserBySmth($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getDepartmentsList()
    {
        $result = $this->users->getDepartmentsList();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getDepartmentAnalyticsList()
    {
        $result = $this->users->getDepartmentAnalyticsList();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getUsersAnalyticsList()
    {
        $result = $this->users->getUsersAnalyticsList();
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function getMailSettingsList(MailsettingsUserRequest $request)
    {
        $data = $request->data();
        $result = $this->users->getMailSettingsList($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function updateMailSettingStatus(MailsettingsUserRequest $request)
    {
        $data = $request->data();
        $result = $this->users->updateMailSettingStatus($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addMailSettingsByUserId(UserRequest $request)
    {
        $data = $request->data();
        $result = $this->users->addMailSettingsByUserId($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function addMailSettingUser(MailsettingsUserRequest $request)
    {
        $data = $request->data();
        $result = $this->users->addMailSettingUser($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function checkMailSetting(MailsettingsUserRequest $request)
    {
        $data = $request->data();
        $result = $this->users->checkMailSetting($data);
        return response()->json(["error" => "0", "result" => $result]);
    }

    public function checkUserRole(UserRoleRequest $request)
    {
        $data = $request->data();
        $result = $this->users->checkUserRole($data);
        return response()->json(["error" => "0", "result" => $result]);
    }
}
