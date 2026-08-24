<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;

class KomplainController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.komplain.index');
    }
}