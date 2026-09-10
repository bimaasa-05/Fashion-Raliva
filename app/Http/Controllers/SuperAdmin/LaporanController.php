<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Store;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
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
