<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class ManajemenTokoController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.manajemen-toko.index');
    }
}