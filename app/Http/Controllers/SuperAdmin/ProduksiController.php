<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class ProduksiController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.produksi.index');
    }
}