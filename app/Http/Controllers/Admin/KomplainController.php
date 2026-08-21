<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class KomplainController extends Controller
{
    public function index()
    {
        return view('Admin.komplain.index');
    }
}