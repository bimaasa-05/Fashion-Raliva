<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Produksi.dashboard.index')->with([
            'storeSuspended' => \App\Support\StoreGate::isLocked(),
            'suspendedStores' => \App\Support\StoreGate::suspendedStoreNames(),
        ]);
    }
}
