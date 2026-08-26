<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

class KelolaSlotController extends Controller
{
    public function index()
    {
        return view('Owner.kelola-slot.index');
    }
}
