<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengaturanTokoController extends Controller
{
    public function index(Request $request)
    {
        // Pengaturan toko sementara diarahkan ke Data Toko sampai halaman
        // pengaturan operasional (toggle pesanan, katalog, notifikasi) siap.
        return redirect()->route('owner.data-toko');
    }
}
