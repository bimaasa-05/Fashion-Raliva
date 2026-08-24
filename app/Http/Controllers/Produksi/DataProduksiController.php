<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;

class DataProduksiController extends Controller
{
    public function index()
    {
        return view('Produksi.data-produksi.index');
    }
}
