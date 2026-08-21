<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.menu.index');
    }
}