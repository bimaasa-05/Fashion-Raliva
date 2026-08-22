<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;

class BarangKeluarController extends Controller
{
    public function index()
    {
        return view('Gudang.barang-keluar.index');
    }
}