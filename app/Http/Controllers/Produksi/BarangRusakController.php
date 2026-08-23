<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;

class BarangRusakController extends Controller
{
    public function index()
    {
        return view('Produksi.barang-rusak.index');
    }
}
