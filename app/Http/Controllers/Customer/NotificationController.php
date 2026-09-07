<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Halaman notifikasi milik user dari data DB.
     */
    public function index()
    {
        $notifications = Auth::user()
            ->ralivaNotifications()
            ->orderByDesc('created_at')
            ->get();

        $unreadCount = $notifications->whereNull('dibaca_pada')->count();

        return view('customer.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     */
    public function markRead(Request $request, Notification $notification)
    {
        abort_if($notification->user_id !== Auth::id(), 403);

        if ($notification->dibaca_pada === null) {
            $notification->update(['dibaca_pada' => now()]);
        }

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }

    /**
     * Tandai semua notifikasi user sebagai sudah dibaca.
     */
    public function markAllRead(Request $request)
    {
        Auth::user()->ralivaNotifications()
            ->whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back();
    }
}