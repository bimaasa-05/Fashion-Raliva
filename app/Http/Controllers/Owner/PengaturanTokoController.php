<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class PengaturanTokoController extends Controller
{
    public function index()
    {
        return view('Owner.pengaturan-toko.index');
    }
}

