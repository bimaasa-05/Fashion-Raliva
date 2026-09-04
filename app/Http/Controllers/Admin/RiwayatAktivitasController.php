<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\StoreStaff;
use App\Support\AdminContext;
use Illuminate\Http\Request;

class RiwayatAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $userIds = StoreStaff::whereIn('store_id', $storeIds)->pluck('user_id')->push(auth()->id())->unique()->filter();

        $logs = ActivityLog::with('user')
            ->whereIn('user_id', $userIds)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('Admin.riwayat-aktivitas.index', compact('logs'));
    }
}
