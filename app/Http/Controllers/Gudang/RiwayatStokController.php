<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;

class RiwayatStokController extends Controller
{
    public function index()
    {
        return view('Gudang.riwayat-stok.index');
    }
}