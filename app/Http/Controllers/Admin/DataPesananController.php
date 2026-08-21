<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DataPesananController extends Controller
{
    public function index()
    {
        return view('Admin.pesanan.index');
    }
}