<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class PengaturanSistemController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.pengaturan-sistem.index');
    }
}
