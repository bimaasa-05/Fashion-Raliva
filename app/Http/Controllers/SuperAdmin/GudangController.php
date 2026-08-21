<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class GudangController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.gudang.index');
    }
}