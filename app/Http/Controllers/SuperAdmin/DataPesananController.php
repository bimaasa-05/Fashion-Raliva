<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataPesananController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.data-pesanan.index');
    }
}