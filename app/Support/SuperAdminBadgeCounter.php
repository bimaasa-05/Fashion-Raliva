<?php

namespace App\Support;

use App\Models\ActivityLog;
use App\Models\AdSlot;
use App\Models\Complaint;
use App\Models\CustomerTopup;
use App\Models\CustomerWithdrawal;
use App\Models\Product;
use App\Models\ProductUpdateRequest;
use App\Models\Refund;
use App\Models\Role;
use App\Models\SlotPurchaseRequest;
use App\Models\Store;
use App\Models\StoreUpdateRequest;
use App\Models\Withdrawal;

class SuperAdminBadgeCounter
{
    public static function counts(): array
    {
        $aktivitas = ActivityLog::where('activity_log_id', '>', (int) session('sa_activity_seen', 0))
            ->whereHas('user', fn ($q) => $q->whereHas('role', fn ($r) => $r->where('nama_role', Role::SUPER_ADMIN)))
            ->count();

        return [
            'toko' => Store::where('status', Store::STATUS_PENDING)->count()
                + StoreUpdateRequest::where('status', StoreUpdateRequest::STATUS_PENDING)->count(),
            'produk' => Product::where('status', Product::STATUS_PENDING)->count(),
            'perubahan_produk' => ProductUpdateRequest::where('status', ProductUpdateRequest::STATUS_PENDING)->count(),
            'slot' => SlotPurchaseRequest::where('status', SlotPurchaseRequest::STATUS_PENDING)->count(),
            'komplain' => Complaint::whereIn('status', [Complaint::STATUS_OPEN, Complaint::STATUS_DIPROSES, Complaint::STATUS_ESKALASI])->count(),
            'iklan' => AdSlot::where('status', AdSlot::STATUS_DITUNDA)->count(),
            'refund' => Refund::where('status', Refund::STATUS_REQUESTED)->count(),
            'penarikan' => Withdrawal::where('status', Withdrawal::STATUS_PENDING)->count(),
            'topup' => CustomerTopup::whereIn('status', [CustomerTopup::STATUS_PENDING, CustomerTopup::STATUS_MENUNGGU_VERIFIKASI])->count(),
            'penarikan_saldo' => CustomerWithdrawal::where('status', CustomerWithdrawal::STATUS_PENDING)->count(),
            'aktivitas' => $aktivitas,
        ];
    }
}
