<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class ProduksiController extends Controller
{
    public function index()
    {
        return view('Owner.produksi.index');
    }
}

