<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;

class RiwayatProduksiController extends Controller
{
    public function index()
    {
        return view('Produksi.riwayat-produksi.index');
    }
}
