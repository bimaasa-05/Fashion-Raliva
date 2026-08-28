<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class PengajuanTokoController extends Controller
{
    public function index(Request $request)
    {
        $store = OwnerContext::currentStore();

        return view('Owner.pengajuan-toko.index', compact('store'));
    }
}
