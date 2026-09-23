<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        $notifications = Notification::with('aktor:user_id,nama_lengkap,foto_profil')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('Gudang.notifikasi.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'notifications' => $notifications,
        ]);
    }

    public function markRead(Request $request)
    {
        Notification::where('user_id', auth()->id())
            ->whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Semua Notifikasi Dibaca', 'Semua notifikasi Anda ditandai sudah dibaca.', route('gudang.notifikasi'));

        return response()->json(['success' => true]);
    }
}
