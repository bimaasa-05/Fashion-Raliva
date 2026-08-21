<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class ManajemenPenggunaController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.manajemen-pengguna.index');
    }
}