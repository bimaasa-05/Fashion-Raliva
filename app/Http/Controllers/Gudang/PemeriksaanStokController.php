<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;

class PemeriksaanStokController extends Controller
{
    public function index()
    {
        return view('Gudang.pemeriksaan.index');
    }
}