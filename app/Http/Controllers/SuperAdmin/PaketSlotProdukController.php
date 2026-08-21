<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class PaketSlotProdukController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.paket-slot-produk.index');
    }
}
