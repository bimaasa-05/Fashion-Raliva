<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PromoController extends Controller
{
    public function index()
    {
        return view('Admin.promo.index');
    }
}