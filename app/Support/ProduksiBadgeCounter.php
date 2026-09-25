<?php

namespace App\Support;

use App\Models\Order;
use App\Models\PermintaanOperasional;
use App\Models\StoreStaff;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class ProduksiBadgeCounter
{
    public static function counts(): array
    {
        $storeIds = StoreStaff::where('user_id', Auth::id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        if (empty($storeIds)) {
            return [
                'produksi' => 0,
                'qc' => 0,
                'siap_kirim' => 0,
                'permintaan' => 0,
                'notifikasi' => 0,
            ];
        }

        return [
            'produksi' => Order::whereIn('store_id', $storeIds)
                ->where('status', Order::STATUS_MENUNGGU_PRODUKSI)
                ->count(),
            'qc' => Order::whereIn('store_id', $storeIds)
                ->where('status', Order::STATUS_MENUNGGU_QC)
                ->count(),
            'siap_kirim' => Order::whereIn('store_id', $storeIds)
                ->where('status', Order::STATUS_SIAP_KIRIM)
                ->count(),
            'permintaan' => PermintaanOperasional::where('pemohon_id', Auth::id())
                ->where('status', PermintaanOperasional::STATUS_PENDING)
                ->count(),
            'notifikasi' => NotificationService::unreadCount(Auth::id()),
        ];
    }
}