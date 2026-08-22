<?php

namespace App\Http\Controllers\SuperAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PromoPlatformController extends Controller
{
    public function index()
    {
        return view('SuperAdmin.promo-platform.index');
    }
}