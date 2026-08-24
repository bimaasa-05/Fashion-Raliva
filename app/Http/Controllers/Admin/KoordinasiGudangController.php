<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class KoordinasiGudangController extends Controller
{
    public function index()
    {
        return view('Admin.koordinasi-gudang.index');
    }
}