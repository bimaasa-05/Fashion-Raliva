<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;

class ProdukSelesaiController extends Controller
{
    public function index()
    {
        return view('Produksi.produk-selesai.index');
    }
}
