<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class ModerasiProdukController extends Controller
{
    public function index()
    {
        return view('Owner.moderasi-produk.index');
    }
}

