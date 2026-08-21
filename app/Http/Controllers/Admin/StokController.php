<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class StokController extends Controller
{
    public function index()
    {
        return view('Admin.stok.index');
    }
}