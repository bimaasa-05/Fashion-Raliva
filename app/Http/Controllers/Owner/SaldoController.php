<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class SaldoController extends Controller
{
    public function index()
    {
        return view('Owner.saldo.index');
    }
}

