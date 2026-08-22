<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class SaldoTokoController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.saldo-toko.index');
    }
}