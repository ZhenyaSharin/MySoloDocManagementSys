<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function lettersList()
    {
        return view('letterslist');
    }

    public function userPage($login)
    {
        return view('userpage', compact('login'));
    }

    public function assignPage($id)
    {
        return view('assignpage', compact('id'));
    }

    public function assignList()
    {
        return view('assignslist');
    }

    public function agreementsList()
    {
        return view('agreementslist');
    }

    public function newAgreements()
    {
        return view('newagreementslist');
    }

    public function agreementHistory()
    {
        return view('historylist');
    }

    public function analyticsPage()
    {
        return view('analytics');
    }

    public function searchPage()
    {
        return view('search');
    }

    public function archivedocsList()
    {
        return view('archivedocslist');
    }

    public function outlettersList()
    {
        return view('outletterslist');
    }

    public function contractsList()
    {
        return view('contractslist');
    }

    public function ordersODList()
    {
        return view('ordersodlist');
    }

    public function memosList()
    {
        return view('memoslist');
    }

    public function otherDocsList()
    {
        return view('otherdocslist');
    }

    public function notificationsList()
    {
        return view('notificationslist');
    }

    public function orDocsList()
    {
        return view('ordocslist');
    }

    public function additionalAgreementsList()
    {
        return view('addagreementslist');
    }

    public function socsList()
    {
        return view('socslist');
    }

    public function adminRegister()
    {
        return view('adminregister');
    }
}
