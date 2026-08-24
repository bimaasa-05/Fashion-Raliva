<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;

class BarangMasukController extends Controller
{
    public function index()
    {
        return view('Gudang.barang-masuk.index');
    }
}