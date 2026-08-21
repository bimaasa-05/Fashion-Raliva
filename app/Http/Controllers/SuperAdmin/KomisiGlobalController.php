<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class KomisiGlobalController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.komisi-global.index');
    }
}