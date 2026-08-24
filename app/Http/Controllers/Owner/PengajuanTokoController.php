<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class PengajuanTokoController extends Controller
{
    public function index()
    {
        return view('Owner.pengajuan-toko.index');
    }
}

