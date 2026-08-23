<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;

class PemeriksaanKualitasController extends Controller
{
    public function index()
    {
        return view('Produksi.pemeriksaan-kualitas.index');
    }
}
