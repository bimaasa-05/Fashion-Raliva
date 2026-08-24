<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class KomplainController extends Controller
{
    public function index()
    {
        return view('Owner.komplain.index');
    }
}

