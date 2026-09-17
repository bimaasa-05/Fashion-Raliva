<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentVerification;
use App\Models\Refund;
use App\Models\StoreExpense;

class KaryawanReportService
{
    /**
     * @param  int[]  $storeIds
     */
    public function pendapatanKaryawan(int $userId, array $storeIds): float
    {
        return (float) Order::whereIn('store_id', $storeIds)
            ->where('status', Order::STATUS_SELESAI)
            ->whereHas('checkout.payment.verifications', function ($q) use ($userId) {
                $q->where('verifier_id', $userId)
                    ->where('status', PaymentVerification::STATUS_DITERIMA);
            })
            ->sum('grand_total');
    }

    /**
     * @param  int[]  $storeIds
     */
    public function pesananKaryawan(int $userId, array $storeIds): int
    {
        return (int) Order::whereIn('store_id', $storeIds)
            ->where('status', Order::STATUS_SELESAI)
            ->whereHas('checkout.payment.verifications', function ($q) use ($userId) {
                $q->where('verifier_id', $userId)
                    ->where('status', PaymentVerification::STATUS_DITERIMA);
            })
            ->count();
    }

    /**
     * @param  int[]  $storeIds
     */
    public function refundKaryawan(int $userId, array $storeIds): float
    {
        return (float) Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
            ->whereIn('orders.store_id', $storeIds)
            ->where('refunds.reviewed_by', $userId)
            ->whereIn('refunds.status', [Refund::STATUS_DISETUJUI, Refund::STATUS_SELESAI])
            ->sum('refunds.jumlah');
    }

    /**
     * @param  int[]  $storeIds
     */
    public function expenseKaryawan(int $userId, array $storeIds): float
    {
        return (float) StoreExpense::whereIn('store_id', $storeIds)
            ->where('dibuat_oleh', $userId)
            ->sum('nominal');
    }

    /**
     * @param  int[]  $storeIds
     */
    public function rekapKaryawan(int $userId, array $storeIds): array
    {
        $pendapatan = $this->pendapatanKaryawan($userId, $storeIds);
        $refund = $this->refundKaryawan($userId, $storeIds);
        $expense = $this->expenseKaryawan($userId, $storeIds);
        $pengeluaran = $refund + $expense;

        return [
            'pesanan' => $this->pesananKaryawan($userId, $storeIds),
            'pendapatan' => $pendapatan,
            'refund' => $refund,
            'expense' => $expense,
            'pengeluaran' => $pengeluaran,
            'bersih' => $pendapatan - $pengeluaran,
        ];
    }
}