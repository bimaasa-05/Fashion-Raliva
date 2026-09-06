<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class PengaturanTokoController extends Controller
{
    public function index(Request $request)
    {
        $store = OwnerContext::currentStore();

        return view('Owner.pengaturan-toko.index', compact('store'));
    }
}
