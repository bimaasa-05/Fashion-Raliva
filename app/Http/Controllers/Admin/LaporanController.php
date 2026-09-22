<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use App\Models\StoreExpense;
use App\Services\KaryawanReportService;
use App\Support\AdminContext;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(
        protected KaryawanReportService $karyawanReport,
    ) {
    }

    public function index(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $storeId = $storeIds[0] ?? null;

        // pendapatan = sum grand_total where status selesai for admin's stores
        $pendapatan = $storeId ? (float) Order::whereIn('store_id', $storeIds)->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])->sum('grand_total') : 0;
        $pesananDiproses = $storeId ? Order::whereIn('store_id', $storeIds)->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])->count() : 0;

        // pengeluaran = refund disetujui/selesai + store expense
        $refund = $storeId ? (float) Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
            ->whereIn('orders.store_id', $storeIds)->whereIn('refunds.status', ['disetujui', 'selesai'])->sum('refunds.jumlah') : 0;
        $expense = $storeId ? (float) StoreExpense::whereIn('store_id', $storeIds)->sum('nominal') : 0;
        $totalPengeluaran = $refund + $expense;
        $totalBersih = $pendapatan - $totalPengeluaran;

        // per admin ("Penjualanku") — pendapatan = order yg payment-nya diverifikasi admin ini
        $saya = $storeIds && auth()->check() && auth()->id()
            ? $this->karyawanReport->rekapKaryawan((int) auth()->id(), $storeIds)
            : ['pesanan' => 0, 'pendapatan' => 0.0, 'refund' => 0.0, 'expense' => 0.0, 'pengeluaran' => 0.0, 'bersih' => 0.0];

        // status counts for admin
        $pesananBaru = $storeId ? Order::whereIn('store_id', $storeIds)->whereIn('status', ['pending_payment','dibayar'])->count() : 0;
        $menungguVerifikasi = $storeId ? \App\Models\Payment::whereHas('checkout.orders', fn($q)=>$q->whereIn('store_id',$storeIds))->where('status','menunggu_verifikasi')->count() : 0;

        // per metode pembayaran breakdown (hanya payment terverifikasi di store scope)
        $perMetode = collect();
        if ($storeIds) {
            $perMetode = \App\Models\Payment::query()
                ->where('status', \App\Models\Payment::STATUS_TERVERIFIKASI)
                ->whereHas('checkout.orders', fn ($q) => $q->whereIn('store_id', $storeIds))
                ->with('paymentMethod')
                ->get()
                ->groupBy('payment_method_id')
                ->map(function ($group) {
                    $metode = $group->first()->paymentMethod;

                    return (object) [
                        'nama_metode' => $metode?->nama_metode ?? '-',
                        'jumlah_transaksi' => $group->count(),
                        'total' => (float) $group->sum('jumlah'),
                    ];
                })
                ->sortByDesc('total')
                ->values();
        }

        // Omzet trend bars — filter dari/sampai (default 30 hari)
        $dari = $request->date('dari')?->startOfDay() ?? now()->subDays(29)->startOfDay();
        $sampai = $request->date('sampai')?->endOfDay() ?? now()->endOfDay();
        if ($sampai->lt($dari)) [$dari, $sampai] = [$sampai->copy()->startOfDay(), $dari->copy()->endOfDay()];
        if ($dari->diffInDays($sampai) > 93) $dari = $sampai->copy()->subDays(92)->startOfDay();

        $omzetBars = [];
        if ($storeIds) {
            $validStatuses = [Order::STATUS_DIBAYAR, Order::STATUS_MENUNGGU_PRODUKSI, Order::STATUS_DIPROSES, Order::STATUS_MENUNGGU_QC, Order::STATUS_SIAP_KIRIM, Order::STATUS_DIKIRIM, Order::STATUS_SELESAI, Order::STATUS_REFUND];
            $dailyRows = Order::query()
                ->whereIn('store_id', $storeIds)
                ->whereIn('status', $validStatuses)
                ->whereBetween('created_at', [$dari, $sampai])
                ->groupBy('tanggal')
                ->selectRaw('DATE(created_at) as tanggal, SUM(grand_total) as total')
                ->pluck('total', 'tanggal');

            $jumlahHari = $dari->diffInDays($sampai);
            foreach (range($jumlahHari, 0) as $i) {
                $hari = $sampai->copy()->subDays($i);
                $kunci = $hari->toDateString();
                $nilai = (float) ($dailyRows[$kunci] ?? 0);
                $omzetBars[] = ['label' => $hari->format('d/m'), 'value' => round($nilai / 1000000, 2)];
            }
        }

        // Donut chart per metode pembayaran
        $palette = ['#C9A24D', '#E9CE8A', '#795905', '#4ade80', '#3b82f6', '#a855f7'];
        $distribusiMetode = $perMetode->map(function ($m, $idx) use ($palette) {
            return [
                'value' => (int) $m->jumlah_transaksi,
                'color' => $palette[$idx % count($palette)],
                'label' => $m->nama_metode,
            ];
        })->values()->all();

        return view('Admin.laporan.index', compact('pendapatan', 'pesananDiproses', 'totalPengeluaran', 'totalBersih', 'perMetode', 'pesananBaru', 'menungguVerifikasi', 'saya', 'omzetBars', 'distribusiMetode', 'dari', 'sampai'));
    }
}