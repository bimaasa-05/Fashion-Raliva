<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;

class NotifikasiController extends Controller
{
    public function index()
    {
        return view('Gudang.notifikasi.index');
    }
}