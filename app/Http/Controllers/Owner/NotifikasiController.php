<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class NotifikasiController extends Controller
{
    public function index()
    {
        return view('Owner.notifikasi.index');
    }
}

