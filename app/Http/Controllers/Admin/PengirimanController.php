<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PengirimanController extends Controller
{
    public function index()
    {
        return view('Admin.pengiriman.index');
    }
}