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

        $notifications = Notification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $meta = [
            'stok_menipis' => ['icon' => 'warning', 'tone' => 'warning', 'label' => 'Stok Menipis'],
            'stok_habis' => ['icon' => 'block', 'tone' => 'error', 'label' => 'Stok Habis'],
            'barang_masuk' => ['icon' => 'inventory_2', 'tone' => 'success', 'label' => 'Barang Masuk'],
            'barang_keluar' => ['icon' => 'unarchive', 'tone' => 'info', 'label' => 'Barang Keluar'],
            'pemenuhan' => ['icon' => 'check_circle', 'tone' => 'success', 'label' => 'Pemenuhan'],
            'pemeriksaan' => ['icon' => 'fact_check', 'tone' => 'info', 'label' => 'Pemeriksaan'],
            'pemindahan' => ['icon' => 'swap_horiz', 'tone' => 'info', 'label' => 'Pemindahan'],
            'order' => ['icon' => 'shopping_cart', 'tone' => 'info', 'label' => 'Pesanan'],
            'pembayaran' => ['icon' => 'payments', 'tone' => 'success', 'label' => 'Pembayaran'],
        ];

        $toneClass = [
            'success' => 'bg-secondary-container/20 text-secondary',
            'warning' => 'bg-tertiary-container/20 text-tertiary',
            'error' => 'bg-error/10 text-error',
            'info' => 'bg-surface-container-high text-on-surface-variant',
        ];

        return view('Gudang.notifikasi.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'notifications' => $notifications,
            'meta' => $meta,
            'toneClass' => $toneClass,
        ]);
    }

    public function markRead(Request $request)
    {
        Notification::where('user_id', auth()->id())
            ->whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);

        return response()->json(['success' => true]);
    }
}
