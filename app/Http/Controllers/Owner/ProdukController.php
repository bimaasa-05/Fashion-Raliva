<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class ProdukController extends Controller
{
    public function index()
    {
        return view('Owner.produk.index');
    }
}

