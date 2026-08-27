<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class DataPelangganController extends Controller
{
    public function index()
    {
        return view('Owner.data-pelanggan.index');
    }
}
