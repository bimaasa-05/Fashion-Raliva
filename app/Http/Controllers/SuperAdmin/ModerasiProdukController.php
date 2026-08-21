<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class ModerasiProdukController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.moderasi-produk.index');
    }
}