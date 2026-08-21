<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardOperasionalController extends Controller
{
    public function index()
    {
        return view('Admin.dashboard-operasional.index');
    }
}