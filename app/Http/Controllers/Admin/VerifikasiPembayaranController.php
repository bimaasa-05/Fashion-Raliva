<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class VerifikasiPembayaranController extends Controller
{
    public function index()
    {
        return view('Admin.verifikasi-pembayaran.index');
    }
}