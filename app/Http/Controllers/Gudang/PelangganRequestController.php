<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;

class PelangganRequestController extends Controller
{
    public function index()
    {
        return view('Gudang.pelanggan-request.index');
    }
}
