<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class PesananController extends Controller
{
    public function index()
    {
        return view('Owner.pesanan.index');
    }
}

