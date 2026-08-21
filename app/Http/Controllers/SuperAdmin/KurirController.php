<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KurirController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.kurir.index');
    }
}