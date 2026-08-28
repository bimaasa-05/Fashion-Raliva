<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        $validStatuses = [
            Order::STATUS_DIBAYAR,
            Order::STATUS_DIPROSES,
            Order::STATUS_DIKIRIM,
            Order::STATUS_SELESAI,
        ];

        $totalPendapatan = (float) Order::whereIn('status', $validStatuses)->sum('grand_total');
        $totalPesanan = Order::whereIn('status', $validStatuses)->count();
        $komisiRaliva = (float) Commission::sum('jumlah_komisi');
        $tokoAktif = Store::where('status', Store::STATUS_AKTIF)->count();

        $chart = Order::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as bulan, COUNT(*) as jumlah, SUM(grand_total) as omzet')
            ->whereIn('status', $validStatuses)
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $chartLabels = [];
        $chartData = [];
        foreach ($chart as $row) {
            $chartLabels[] = Carbon::createFromFormat('Y-m', $row->bulan)->locale('id')->translatedFormat('M');
            $chartData[] = round((float) $row->omzet / 1_000_000, 1);
        }

        $topToko = $this->topToko();

        $recentTransactions = Order::with('store:store_id,nama_toko')
            ->whereIn('status', $validStatuses)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('SuperAdmin.laporan.index', [
            'totalPendapatan' => $totalPendapatan,
            'totalPesanan' => $totalPesanan,
            'komisiRaliva' => $komisiRaliva,
            'tokoAktif' => $tokoAktif,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'topToko' => $topToko,
            'recentTransactions' => $recentTransactions,
        ]);
    }

    private function topToko(): array
    {
        $rows = Order::query()
            ->selectRaw('stores.store_id, stores.nama_toko')
            ->selectRaw('SUM(orders.grand_total) as total_omzet')
            ->selectRaw('COUNT(*) as jumlah_pesanan')
            ->join('stores', 'stores.store_id', '=', 'orders.store_id')
            ->whereIn('orders.status', [
                Order::STATUS_DIBAYAR,
                Order::STATUS_DIPROSES,
                Order::STATUS_DIKIRIM,
                Order::STATUS_SELESAI,
            ])
            ->groupBy('stores.store_id', 'stores.nama_toko')
            ->orderByDesc('total_omzet')
            ->limit(5)
            ->get();

        $max = (float) $rows->max('total_omzet') ?: 1;

        return $rows->map(function ($row) use ($max) {
            return [
                'name' => $row->nama_toko,
                'meta' => $row->jumlah_pesanan.' pesanan',
                'display' => 'Rp '.number_format((float) $row->total_omzet / 1_000_000, 0, ',', '').'JT',
                'pct' => max(4, (int) round(((float) $row->total_omzet / $max) * 100)),
            ];
        })->all();
    }
}
