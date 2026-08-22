<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class PencairanDanaController extends Controller
{
    public function index()
    {
        return view('Owner.pencairan-dana.index');
    }
}

