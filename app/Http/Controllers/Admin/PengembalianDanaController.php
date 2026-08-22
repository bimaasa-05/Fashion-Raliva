<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PengembalianDanaController extends Controller
{
    public function index()
    {
        return view('Admin.pengembalian-dana.index');
    }
}