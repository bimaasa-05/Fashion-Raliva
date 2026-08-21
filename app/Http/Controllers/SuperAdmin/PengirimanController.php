<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class PengirimanController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.pengiriman.index');
    }
}