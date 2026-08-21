<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DataProdukController extends Controller
{
    public function index()
    {
        return view('Admin.produk.index');
    }
}