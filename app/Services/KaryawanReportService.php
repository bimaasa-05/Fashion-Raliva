<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentVerification;
use App\Models\ProductionOrder;
use App\Models\QualityCheck;
use App\Models\Refund;
use App\Models\Review;
use App\Models\StockDamage;
use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Models\StockTransfer;
use App\Models\StoreExpense;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class KaryawanReportService
{
    /**
     * Rentang periode [mulai, akhir] atau null bila "semua".
     *
     * @return array{0: ?Carbon, 1: ?Carbon}|null
     */
    public function rentangPeriode(?string $dari, ?string $sampai): ?array
    {
        if (! $dari && ! $sampai) {
            return null;
        }

        try {
            return [
                $dari ? Carbon::parse($dari)->startOfDay() : null,
                $sampai ? Carbon::parse($sampai)->endOfDay() : null,
            ];
        } catch (\Throwable) {
            return null;
        }
    }

    private function dalamRentang(Builder $query, ?array $range, string $kolom = 'created_at'): Builder
    {
        if (! $range) {
            return $query;
        }
        [$mulai, $akhir] = $range;
        if ($mulai) {
            $query->where($kolom, '>=', $mulai);
        }
        if ($akhir) {
            $query->where($kolom, '<=', $akhir);
        }

        return $query;
    }

    /* ================= ADMIN ================= */

    /**
     * @param  int[]  $storeIds
     */
    private function verifikasiQuery(int $userId, array $storeIds, ?array $range, string $status): Builder
    {
        $query = PaymentVerification::where('verifier_id', $userId)
            ->where('status', $status)
            ->whereHas('payment.checkout.orders', fn ($q) => $q->whereIn('store_id', $storeIds));

        return $this->dalamRentang($query, $range, 'diverifikasi_pada');
    }

    /**
     * @param  int[]  $storeIds
     */
    public function rekapAdmin(int $userId, array $storeIds, ?array $range = null): array
    {
        $diterima = (clone $this->verifikasiQuery($userId, $storeIds, $range, PaymentVerification::STATUS_DITERIMA))->count();
        $ditolak = (clone $this->verifikasiQuery($userId, $storeIds, $range, PaymentVerification::STATUS_DITOLAK))->count();
        $ditangani = $diterima + $ditolak;

        $orderQuery = Order::whereIn('store_id', $storeIds)
            ->where('status', Order::STATUS_SELESAI)
            ->whereHas('checkout.payment.verifications', function ($q) use ($userId, $range) {
                $q->where('verifier_id', $userId)
                    ->where('status', PaymentVerification::STATUS_DITERIMA);
                if ($range) {
                    [$mulai, $akhir] = $range;
                    if ($mulai) {
                        $q->where('diverifikasi_pada', '>=', $mulai);
                    }
                    if ($akhir) {
                        $q->where('diverifikasi_pada', '<=', $akhir);
                    }
                }
            });
        $pesanan = (clone $orderQuery)->count();
        $pendapatan = (float) (clone $orderQuery)->sum('grand_total');

        // Prospek = order berbeda yang pernah diverifikasi admin ini (terima/tolak).
        $prospekQuery = Order::whereIn('store_id', $storeIds)
            ->whereHas('checkout.payment.verifications', function ($q) use ($userId, $range) {
                $q->where('verifier_id', $userId);
                if ($range) {
                    [$mulai, $akhir] = $range;
                    if ($mulai) {
                        $q->where('diverifikasi_pada', '>=', $mulai);
                    }
                    if ($akhir) {
                        $q->where('diverifikasi_pada', '<=', $akhir);
                    }
                }
            });
        $prospek = (clone $prospekQuery)->distinct()->count('orders.order_id');

        // LTV = pendapatan / customer unik yang ditangani admin ini.
        $checkoutIds = (clone $orderQuery)->pluck('orders.checkout_id')->unique()->values();
        $customers = $checkoutIds->isNotEmpty()
            ? \App\Models\Checkout::whereIn('checkout_id', $checkoutIds)->distinct()->count('user_id')
            : 0;

        $reviewQuery = $this->dalamRentang(
            Review::whereIn('store_id', $storeIds)
                ->where('status', Review::STATUS_AKTIF)
                ->whereHas('orderItem.order.checkout.payment.verifications', fn ($q) => $q
                    ->where('verifier_id', $userId)
                    ->where('status', PaymentVerification::STATUS_DITERIMA)),
            $range
        );
        $ratingCount = (clone $reviewQuery)->count();
        $rating = $ratingCount > 0 ? round((float) (clone $reviewQuery)->avg('rating'), 2) : null;

        return [
            'diverifikasi' => $diterima,
            'ditolak' => $ditolak,
            'prospek' => $prospek,
            'cr' => $prospek > 0 ? round($pesanan / $prospek * 100, 2) : null,
            'pesanan' => $pesanan,
            'pendapatan' => $pendapatan,
            'aov' => $pesanan > 0 ? round($pendapatan / $pesanan, 2) : null,
            'customers' => $customers,
            'ltv' => $customers > 0 ? round($pendapatan / $customers, 2) : null,
            'rating' => $rating,
            'rating_count' => $ratingCount,
        ];
    }

    /* ================= PRODUKSI ================= */

    /**
     * @param  int[]  $storeIds
     */
    public function rekapProduksi(int $userId, array $storeIds, ?array $range = null): array
    {
        $base = fn () => $this->dalamRentang(
            ProductionOrder::whereIn('store_id', $storeIds)->where('assigned_to', $userId),
            $range
        );

        $ditugaskan = (clone $base())->count();
        $selesai = (clone $base())->where('status', ProductionOrder::STATUS_SELESAI)->count();

        $unitDiminta = (float) (clone $base())
            ->join('production_order_items', 'production_order_items.production_order_id', '=', 'production_orders.production_order_id')
            ->sum('production_order_items.jumlah_diminta');

        $qcBase = QualityCheck::whereHas('productionOrder', fn ($q) => $q
            ->whereIn('store_id', $storeIds)->where('assigned_to', $userId));
        $qcBase = $this->dalamRentang($qcBase, $range);
        $outputLayak = (int) (clone $qcBase)->sum('jumlah_lulus');
        $orderQc = (clone $qcBase)->distinct()->count('production_order_id');

        $durasiSelesai = (clone $base())
            ->where('status', ProductionOrder::STATUS_SELESAI)
            ->whereNotNull('dimulai_pada')
            ->whereNotNull('selesai_pada')
            ->get(['dimulai_pada', 'selesai_pada']);
        $durasiJam = $durasiSelesai
            ->map(fn ($o) => $o->dimulai_pada->diffInHours($o->selesai_pada))
            ->filter(fn ($h) => $h >= 0);

        return [
            'ditugaskan' => $ditugaskan,
            'selesai' => $selesai,
            'sukses_persen' => $ditugaskan > 0 ? round($selesai / $ditugaskan * 100, 2) : null,
            'rata_unit_diminta' => $ditugaskan > 0 ? round($unitDiminta / $ditugaskan, 2) : null,
            'rata_output_layak' => $orderQc > 0 ? round($outputLayak / $orderQc, 2) : null,
            'rata_durasi_jam' => $durasiJam->isNotEmpty() ? round($durasiJam->avg(), 2) : null,
            'sampel_durasi' => $durasiJam->count(),
        ];
    }

    /* ================= GUDANG ================= */

    /**
     * @param  int[]  $storeIds
     */
    public function rekapGudang(int $userId, array $storeIds, ?array $range = null): array
    {
        $transferBase = fn () => $this->dalamRentang(
            StockTransfer::where('requested_by', $userId)
                ->whereHas('fromWarehouse', fn ($q) => $q->whereIn('store_id', $storeIds)),
            $range
        );

        $diminta = (clone $transferBase())->count();
        $selesaiTf = (clone $transferBase())->where('status', StockTransfer::STATUS_RECEIVED)->count();
        $batalTf = (clone $transferBase())->where('status', StockTransfer::STATUS_CANCELLED)->count();
        $putaran = (clone $transferBase())
            ->where('status', StockTransfer::STATUS_RECEIVED)
            ->whereNotNull('diminta_pada')
            ->whereNotNull('diterima_pada')
            ->get(['diminta_pada', 'diterima_pada'])
            ->map(fn ($t) => $t->diminta_pada->diffInHours($t->diterima_pada))
            ->filter(fn ($h) => $h >= 0);

        $stokToko = function (string $model) use ($userId, $storeIds, $range): Builder {
            return $this->dalamRentang(
                $model::where('dibuat_oleh', $userId)
                    ->whereHas('warehouse', fn ($w) => $w->whereIn('store_id', $storeIds)),
                $range
            );
        };

        $mutasi = (clone $stokToko(StockMovement::class))->count();
        $opnameQ = $stokToko(StockOpname::class);
        $opname = (clone $opnameQ)->count();
        $opnameAkurat = (clone $opnameQ)->where('selisih', 0)->count();
        $rusakQ = $stokToko(StockDamage::class);
        $kerusakan = (clone $rusakQ)->count();
        $kerusakanQty = (int) (clone $rusakQ)->sum('jumlah_rusak');

        return [
            'transfer_diminta' => $diminta,
            'transfer_selesai' => $selesaiTf,
            'transfer_batal' => $batalTf,
            'rata_putaran_jam' => $putaran->isNotEmpty() ? round($putaran->avg(), 2) : null,
            'sampel_putaran' => $putaran->count(),
            'mutasi' => $mutasi,
            'opname' => $opname,
            'akurasi_persen' => $opname > 0 ? round($opnameAkurat / $opname * 100, 2) : null,
            'kerusakan' => $kerusakan,
            'kerusakan_qty' => $kerusakanQty,
        ];
    }

    /* ================= KEUANGAN OWNER ================= */

    /**
     * @param  int[]  $storeIds
     */
    public function ringkasanKeuangan(array $storeIds, ?array $range = null): array
    {
        $orders = $this->dalamRentang(
            Order::whereIn('orders.store_id', $storeIds)->where('orders.status', Order::STATUS_SELESAI),
            $range,
            'orders.created_at'
        );
        $revenue = (float) (clone $orders)->sum('orders.grand_total');
        $customers = (clone $orders)
            ->join('checkouts', 'checkouts.checkout_id', '=', 'orders.checkout_id')
            ->distinct()
            ->count('checkouts.user_id');

        $refunds = (float) $this->dalamRentang(
            Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
                ->whereIn('orders.store_id', $storeIds)
                ->where('refunds.status', Refund::STATUS_SELESAI),
            $range,
            'refunds.created_at'
        )->sum('refunds.jumlah');

        $expenses = (float) $this->dalamRentang(
            StoreExpense::whereIn('store_id', $storeIds),
            $range,
            'tanggal'
        )->sum('nominal');

        $ads = (float) $this->dalamRentang(
            WalletTransaction::where('jenis_transaksi', WalletTransaction::JENIS_BIAYA_IKLAN)
                ->whereHas('wallet', fn ($q) => $q->whereIn('store_id', $storeIds)),
            $range
        )->sum('jumlah');

        $investasi = (float) $this->dalamRentang(
            WalletTransaction::where('jenis_transaksi', WalletTransaction::JENIS_PEMASUKAN)
                ->whereIn('kategori', ['Modal', 'Investor'])
                ->whereHas('wallet', fn ($q) => $q->whereIn('store_id', $storeIds)),
            $range
        )->sum('jumlah') + $ads;

        $bersih = $revenue - $refunds - $expenses - $ads;

        return [
            'revenue' => $revenue,
            'refunds' => $refunds,
            'expenses' => $expenses,
            'ads' => $ads,
            'bersih' => $bersih,
            'investasi' => $investasi,
            'roi' => $investasi > 0 ? round($bersih / $investasi * 100, 2) : null,
            'ltv' => $customers > 0 ? round($revenue / $customers, 2) : null,
            'customers' => $customers,
        ];
    }

    /**
     * @param  int[]  $storeIds
     */
    public function pendapatanKaryawan(int $userId, array $storeIds): float
    {
        return (float) Order::whereIn('store_id', $storeIds)
            ->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])
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
            ->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])
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
            ->where('refunds.status', Refund::STATUS_SELESAI)
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