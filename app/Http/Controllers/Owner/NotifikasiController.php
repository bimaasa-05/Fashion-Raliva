<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $today = Notification::where('user_id', $userId)
            ->whereDate('created_at', now()->toDateString())
            ->orderByDesc('created_at')
            ->get();

        $earlier = Notification::where('user_id', $userId)
            ->whereDate('created_at', '<', now()->toDateString())
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $unread = Notification::where('user_id', $userId)->whereNull('dibaca_pada')->count();

        return view('Owner.notifikasi.index', compact('today', 'earlier', 'unread'));
    }
}
