<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;

class PemindahanStokController extends Controller
{
    public function index()
    {
        return view('Gudang.pemindahan.index');
    }
}
