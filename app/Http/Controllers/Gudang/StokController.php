<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;

class StokController extends Controller
{
    public function index()
    {
        return view('Gudang.stok.index');
    }
}