<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PajakBiayaController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.pajak-biaya.index');
    }
}