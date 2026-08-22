<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PermintaanProduksiController extends Controller
{
    public function index()
    {
        return view('Admin.permintaan-produksi.index');
    }
}