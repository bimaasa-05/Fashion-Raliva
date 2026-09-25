<?php

namespace App\Support;

use App\Models\Complaint;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PermintaanOperasional;
use App\Models\Refund;

class AdminBadgeCounter
{
    public static function counts(): array
    {
        $storeIds = AdminContext::assignedStoreIds();

        if (empty($storeIds)) {
            return [
                'pesanan' => 0,
                'pembayaran' => 0,
                'refund' => 0,
                'komplain' => 0,
                'permintaan' => 0,
            ];
        }

        return [
            'pesanan' => Order::whereIn('store_id', $storeIds)
                ->where('status', Order::STATUS_DIBAYAR)
                ->count(),
            'pembayaran' => Payment::whereIn('checkout_id', Order::whereIn('store_id', $storeIds)->select('checkout_id'))
                ->where('status', Payment::STATUS_MENUNGGU_VERIFIKASI)
                ->count(),
            'refund' => Refund::whereHas('order', fn ($q) => $q->whereIn('store_id', $storeIds))
                ->whereIn('status', [Refund::STATUS_REQUESTED, Refund::STATUS_ESKALASI])
                ->count(),
            'komplain' => Complaint::whereHas('order', fn ($q) => $q->whereIn('store_id', $storeIds))
                ->whereIn('status', [Complaint::STATUS_OPEN, Complaint::STATUS_DIPROSES])
                ->count(),
            'permintaan' => PermintaanOperasional::whereIn('store_id', $storeIds)
                ->where('status', PermintaanOperasional::STATUS_PENDING)
                ->count(),
        ];
    }
}