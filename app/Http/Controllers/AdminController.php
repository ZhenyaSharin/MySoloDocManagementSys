<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin');
    }

    // public function refresh()
    // {
    //     $result = exec('php /www/site/docmanagementsystem/artisan config:cache');
    //     return $result;
    // }
}
