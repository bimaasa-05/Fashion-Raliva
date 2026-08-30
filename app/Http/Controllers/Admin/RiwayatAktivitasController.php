<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RiwayatAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('Admin.riwayat-aktivitas.index', compact('logs'));
    }
}
