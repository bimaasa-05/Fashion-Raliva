<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\NotifikasiController as RootNotifikasiController;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends RootNotifikasiController
{
    public function index(Request $request)
    {
        $notifications = Notification::with('aktor:user_id,nama_lengkap,foto_profil')
            ->forUser(Auth::id())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('Produksi.notifikasi.index', [
            'notifications' => $notifications,
        ]);
    }
}