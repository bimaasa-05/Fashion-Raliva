<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class PermintaanPenarikanController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.permintaan-penarikan.index');
    }
}