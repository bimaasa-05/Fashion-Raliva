<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class DataPembayaranController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.data-pembayaran.index');
    }
}
