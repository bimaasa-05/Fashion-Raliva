<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class DataTokoController extends Controller
{
    public function index()
    {
        return view('Owner.data-toko.index');
    }
}

