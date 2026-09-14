<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Store;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

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

        // Data grafik Tren Pendapatan: 7 hari, 1 bulan, 1 tahun (pre-computed utk dropdown)
        $rangeData = [
            '7' => $this->chartRange(7),
            '30' => $this->chartRange(30),
            '365' => $this->chartRange(365),
        ];
        $activeRange = '30';

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
            'rangeData' => $rangeData,
            'activeRange' => $activeRange,
            'topToko' => $topToko,
            'recentTransactions' => $recentTransactions,
        ]);
    }

    public function export(Request $request)
    {
        $period = (int) $request->input('period', 30);
        if (! in_array($period, [7, 30, 90, 365])) {
            $period = 30;
        }

        $bucket = function ($start, $end, $label) {
            return [
                $label,
                Order::whereBetween('created_at', [$start, $end])->count(),
                (float) Order::where('status', Order::STATUS_SELESAI)->whereBetween('created_at', [$start, $end])->sum('grand_total'),
                (float) Refund::where('status', Refund::STATUS_SELESAI)->whereBetween('diajukan_pada', [$start, $end])->sum('jumlah'),
                (float) Withdrawal::where('status', Withdrawal::STATUS_DIBAYAR)->whereBetween('diajukan_pada', [$start, $end])->sum('jumlah'),
            ];
        };

        $rows = [];
        if ($period <= 7) {
            for ($i = $period - 1; $i >= 0; $i--) {
                $day = now()->subDays($i);
                $rows[] = $bucket($day->copy()->startOfDay(), $day->copy()->endOfDay(), $day->translatedFormat('d M Y'));
            }
        } elseif ($period <= 30) {
            for ($i = 3; $i >= 0; $i--) {
                $end = now()->subDays($i * 7);
                $start = $end->copy()->subDays(6)->startOfDay();
                $rows[] = $bucket($start, $end->copy()->endOfDay(), $start->translatedFormat('d').' — '.$end->translatedFormat('d M'));
            }
        } else {
            $months = $period <= 90 ? 3 : 12;
            for ($i = $months - 1; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $rows[] = $bucket($month->copy()->startOfMonth(), $month->copy()->endOfMonth(), $month->translatedFormat('M Y'));
            }
        }

        $fileName = 'laporan-superadmin-'.now()->translatedFormat('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, ['Periode', 'Pesanan', 'Pendapatan', 'Refund', 'Pencairan']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r[0],
                    $r[1],
                    number_format($r[2], 0, ',', '.'),
                    number_format($r[3], 0, ',', '.'),
                    number_format($r[4], 0, ',', '.'),
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function chartRange(int $days): array
    {
        $validStatuses = [
            Order::STATUS_DIBAYAR,
            Order::STATUS_DIPROSES,
            Order::STATUS_DIKIRIM,
            Order::STATUS_SELESAI,
        ];

        if ($days === 365) {
            $rows = Order::query()
                ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as periode, SUM(grand_total) as omzet')
                ->whereIn('status', $validStatuses)
                ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
                ->groupBy('periode')
                ->orderBy('periode')
                ->get()
                ->pluck('omzet', 'periode');

            $labels = [];
            $data = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $labels[] = $month->locale('id')->translatedFormat('M Y');
                $data[] = (float) ($rows[$month->format('Y-m')] ?? 0);
            }
        } else {
            $rows = Order::query()
                ->selectRaw('DATE(created_at) as periode, SUM(grand_total) as omzet')
                ->whereIn('status', $validStatuses)
                ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
                ->groupBy('periode')
                ->orderBy('periode')
                ->get()
                ->pluck('omzet', 'periode');

            $labels = [];
            $data = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $day = now()->subDays($i);
                $labels[] = $day->locale('id')->translatedFormat('d M');
                $data[] = (float) ($rows[$day->toDateString()] ?? 0);
            }
        }

        $total = array_sum($data);
        $max = $total > 0 ? max($data) : 0;
        $bestIdx = $max > 0 ? array_search($max, $data, true) : -1;

        return [
            'labels' => $labels,
            'data' => $data,
            'total' => $total,
            'best' => $bestIdx >= 0 ? ['label' => $labels[$bestIdx], 'value' => $data[$bestIdx]] : null,
        ];
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
                'display' => 'Rp '.number_format((float) $row->total_omzet, 0, ',', '.'),
                'pct' => max(4, (int) round(((float) $row->total_omzet / $max) * 100)),
            ];
        })->all();
    }
}
