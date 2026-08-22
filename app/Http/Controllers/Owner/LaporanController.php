<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class LaporanController extends Controller
{
    public function index()
    {
        return view('Owner.laporan.index');
    }
}

