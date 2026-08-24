<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class PromoController extends Controller
{
    public function index()
    {
        return view('Owner.promo.index');
    }
}

