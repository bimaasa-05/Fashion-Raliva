<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KategoriProdukController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.kategori-produk.index');
    }
}