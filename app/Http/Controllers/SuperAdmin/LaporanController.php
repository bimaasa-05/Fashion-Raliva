<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.laporan.index');
    }
}