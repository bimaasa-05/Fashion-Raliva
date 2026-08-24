<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;

class PermintaanProduksiController extends Controller
{
    public function index()
    {
        return view('Produksi.permintaan-produksi.index');
    }
}
