<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;

class NotifikasiController extends Controller
{
    public function index()
    {
        return view('Produksi.notifikasi.index');
    }
}
