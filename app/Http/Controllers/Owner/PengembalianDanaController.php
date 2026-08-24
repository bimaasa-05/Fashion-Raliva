<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class PengembalianDanaController extends Controller
{
    public function index()
    {
        return view('Owner.pengembalian-dana.index');
    }
}

