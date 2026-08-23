<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;

class BahanProduksiController extends Controller
{
    public function index()
    {
        return view('Produksi.bahan-produksi.index');
    }
}
