<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class ProdukController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.produk.index');
    }
}