<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class PaketSlotController extends Controller
{
    public function index()
    {
        return view('Owner.paket-slot.index');
    }
}

