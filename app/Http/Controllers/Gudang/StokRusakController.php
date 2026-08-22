<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;

class StokRusakController extends Controller
{
    public function index()
    {
        return view('Gudang.stok-rusak.index');
    }
}