<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class GudangController extends Controller
{
    public function index()
    {
        return view('Owner.gudang.index');
    }
}

