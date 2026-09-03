<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::with('user:user_id,nama_lengkap')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $unread = Notification::whereNull('dibaca_pada')->count();

        return view('SuperAdmin.notifikasi.index', compact('notifications', 'unread'));
    }

    public function markRead()
    {
        Notification::whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);

        return response()->json(['success' => true]);
    }
}
