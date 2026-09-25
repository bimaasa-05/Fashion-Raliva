<?php

namespace App\Support;

use App\Models\Complaint;
use App\Models\Order;
use App\Models\Refund;
use App\Models\StockTransfer;
use App\Models\Withdrawal;

class OwnerBadgeCounter
{
    public static function counts(): array
    {
        $storeId = OwnerContext::firstStoreId();

        if ($storeId === null) {
            return [
                'pesanan' => 0,
                'eskalasi' => 0,
                'penarikan' => 0,
                'gudang' => 0,
            ];
        }

        return [
            'pesanan' => Order::where('store_id', $storeId)
                ->whereIn('status', [Order::STATUS_PENDING_PAYMENT, Order::STATUS_DIBAYAR])
                ->count(),
            'eskalasi' => Complaint::where('store_id', $storeId)
                ->where('status', Complaint::STATUS_ESKALASI)
                ->count()
                + Refund::whereHas('order', fn ($q) => $q->where('store_id', $storeId))
                    ->where('status', Refund::STATUS_ESKALASI)
                    ->count(),
            'penarikan' => Withdrawal::where('store_id', $storeId)
                ->where('status', Withdrawal::STATUS_PENDING)
                ->count(),
            'gudang' => StockTransfer::where(fn ($q) => $q
                ->whereHas('fromWarehouse', fn ($w) => $w->where('store_id', $storeId))
                ->orWhereHas('toWarehouse', fn ($w) => $w->where('store_id', $storeId)))
                ->where('status', StockTransfer::STATUS_REQUESTED)
                ->count(),
        ];
    }
}