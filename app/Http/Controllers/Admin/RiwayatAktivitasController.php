<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class RiwayatAktivitasController extends Controller
{
    public function index()
    {
        return view('Admin.riwayat-aktivitas.index');
    }
}