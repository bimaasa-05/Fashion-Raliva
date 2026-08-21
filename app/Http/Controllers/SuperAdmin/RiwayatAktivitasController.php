<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class RiwayatAktivitasController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.riwayat-aktivitas.index');
    }
}