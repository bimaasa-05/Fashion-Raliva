<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class UlasanController extends Controller
{
    public function index()
    {
        return view('Owner.ulasan.index');
    }
}

