<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Gudang.dashboard.index');
    }
}
