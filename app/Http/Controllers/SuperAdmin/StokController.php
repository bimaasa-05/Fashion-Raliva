<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class StokController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.stok.index');
    }
}