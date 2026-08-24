<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class KaryawanController extends Controller
{
    public function index()
    {
        return view('Owner.karyawan.index');
    }
}

