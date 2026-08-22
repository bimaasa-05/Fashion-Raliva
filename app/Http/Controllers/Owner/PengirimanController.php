<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class PengirimanController extends Controller
{
    public function index()
    {
        return view('Owner.pengiriman.index');
    }
}

