<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class PengembalianDanaController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.pengembalian-dana.index');
    }
}
