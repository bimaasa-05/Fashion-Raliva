<?php

namespace App\Http\Controllers\Owner;

use App\Exports\OwnerLaporanExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Withdrawal;
use App\Models\OrderItem;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId() ?? 0;

        $period = (int) $request->input('period', 30);
        if (! in_array($period, [7, 30, 90, 365])) {
            $period = 30;
        }

        $data = $this->reportData($storeId, $period);

        // chart data per range
        $buildRange = function ($days) use ($storeId) {
            $start = now()->subDays($days - 1)->startOfDay();
            $orders = Order::where('store_id', $storeId)
                ->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])
                ->where('created_at', '>=', $start)
                ->selectRaw('DATE(created_at) as tgl, SUM(grand_total) as pendapatan')
                ->groupBy('tgl')->pluck('pendapatan', 'tgl');

            if ($days <= 30) {
                $labels = []; $pend = []; $ref = [];
                for ($i = $days - 1; $i >= 0; $i -= 7) {
                    $end = now()->subDays($i);
                    $startW = $end->copy()->subDays(6)->startOfDay();
                    $labels[] = $startW->translatedFormat('d M');
                    $pend[] = (float) Order::where('store_id', $storeId)->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])
                        ->whereBetween('created_at', [$startW, $end->endOfDay()])->sum('grand_total');
                    $ref[] = (float) Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
                        ->where('orders.store_id', $storeId)->where('refunds.status', 'selesai')
                        ->whereBetween('refunds.diajukan_pada', [$startW, $end->endOfDay()])->sum('refunds.jumlah');
                }
                return ['labels' => $labels, 'pendapatan' => $pend, 'refund' => $ref];
            }
            $agg = Order::where('store_id', $storeId)->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])
                ->where('created_at', '>=', $start)
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bln, SUM(grand_total) as pendapatan")
                ->groupBy('bln')->orderBy('bln')->pluck('pendapatan', 'bln');
            $labels = $agg->keys()->map(fn($b) => \Carbon\Carbon::createFromFormat('Y-m', $b)->translatedFormat('M Y'))->values()->all();
            return ['labels' => $labels, 'pendapatan' => $agg->values()->all(), 'refund' => []];
        };

        $chartData = [
            '30' => $buildRange(30),
            '90' => $buildRange(90),
            '365' => $buildRange(365),
        ];

        return view('Owner.laporan.index', array_merge($data, ['chartData' => $chartData, 'period' => $period]));
    }

    private function reportData(?int $storeId, int $period): array
    {
        $storeId = $storeId ?? 0;

        $pendapatan = (float) Order::where('store_id', $storeId)->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])->sum('grand_total');
        $pesananSelesai = Order::where('store_id', $storeId)->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])->count();

        $refund = (float) Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
            ->where('orders.store_id', $storeId)
            ->where('refunds.status', 'selesai')
            ->sum('refunds.jumlah');

        $dicairkan = (float) Withdrawal::where('store_id', $storeId)
            ->where('status', 'selesai')
            ->sum('jumlah');

        $bucket = function ($s, $e, $label) use ($storeId) {
            return [
                'periode' => $label,
                'pesanan' => Order::where('store_id', $storeId)->whereBetween('created_at', [$s, $e])->count(),
                'pendapatan' => (float) Order::where('store_id', $storeId)->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])->whereBetween('created_at', [$s, $e])->sum('grand_total'),
                'refund' => (float) Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
                    ->where('orders.store_id', $storeId)->where('refunds.status', 'selesai')
                    ->whereBetween('refunds.diajukan_pada', [$s, $e])->sum('refunds.jumlah'),
                'pencairan' => (float) Withdrawal::where('store_id', $storeId)->where('status', 'selesai')->whereBetween('diajukan_pada', [$s, $e])->sum('jumlah'),
            ];
        };

        $report = [];
        if ($period <= 7) {
            for ($i = $period - 1; $i >= 0; $i--) {
                $day = now()->subDays($i);
                $report[] = $bucket($day->copy()->startOfDay(), $day->copy()->endOfDay(), $day->translatedFormat('d M Y'));
            }
        } elseif ($period <= 30) {
            for ($i = 3; $i >= 0; $i--) {
                $end = now()->subDays($i * 7);
                $s = $end->copy()->subDays(6)->startOfDay();
                $report[] = $bucket($s, $end->copy()->endOfDay(), $s->translatedFormat('d') . ' — ' . $end->translatedFormat('d M'));
            }
        } else {
            $months = $period <= 90 ? 3 : 12;
            for ($i = $months - 1; $i >= 0; $i--) {
                $m = now()->subMonths($i);
                $report[] = $bucket($m->copy()->startOfMonth(), $m->copy()->endOfMonth(), $m->translatedFormat('M Y'));
            }
        }

        $totals = [
            'pesanan' => array_sum(array_column($report, 'pesanan')),
            'pendapatan' => array_sum(array_column($report, 'pendapatan')),
            'refund' => array_sum(array_column($report, 'refund')),
            'pencairan' => array_sum(array_column($report, 'pencairan')),
        ];

        // top products
        $top = OrderItem::query()
            ->select('product_variants.product_id', DB::raw('SUM(order_items.quantity) as terjual'))
            ->join('product_variants', 'product_variants.product_variant_id', '=', 'order_items.product_variant_id')
            ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->where('orders.store_id', $storeId)
            ->where('orders.status', 'selesai')
            ->groupBy('product_variants.product_id')
            ->orderByDesc('terjual')
            ->limit(5)
            ->with('productVariant.product')
            ->get()
            ->map(function ($oi) {
                return [
                    'nama' => $oi->productVariant?->product?->nama_produk ?? '-',
                    'terjual' => (int) $oi->terjual,
                ];
            })->all();

        return compact('pendapatan', 'pesananSelesai', 'refund', 'dicairkan', 'report', 'totals', 'top');
    }

    public function cetak(Request $request)
    {
        $storeId = OwnerContext::firstStoreId() ?? 0;

        $period = (int) $request->input('period', 30);
        if (! in_array($period, [7, 30, 90, 365])) {
            $period = 30;
        }

        $data = $this->reportData($storeId, $period);
        $store = \App\Models\Store::where('store_id', $storeId)->first();

        $periodeLabel = [7 => '1 Minggu', 30 => '30 Hari', 90 => '3 Bulan', 365 => '1 Tahun'][$period] ?? '30 Hari';
        $fmt = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.');

        return view('Owner.laporan.cetak', array_merge($data, [
            'period' => $period,
            'store' => $store,
            'periodeLabel' => $periodeLabel,
            'fmt' => $fmt,
        ]));
    }

    public function exportPdf(Request $request)
    {
        $storeId = OwnerContext::firstStoreId() ?? 0;

        $period = (int) $request->input('period', 30);
        if (! in_array($period, [7, 30, 90, 365])) {
            $period = 30;
        }

        $data = $this->reportData($storeId, $period);
        $store = \App\Models\Store::where('store_id', $storeId)->first();

        $periodeLabel = [7 => '1 Minggu', 30 => '30 Hari', 90 => '3 Bulan', 365 => '1 Tahun'][$period] ?? '30 Hari';
        $fmt = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Owner.laporan.pdf', array_merge($data, [
            'period' => $period,
            'store' => $store,
            'periodeLabel' => $periodeLabel,
            'fmt' => $fmt,
        ]))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-toko-' . now()->translatedFormat('Y-m-d') . '.pdf');
    }

    public function export(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $period = (int) $request->input('period', 30);
        if (! in_array($period, [7, 30, 90, 365])) {
            $period = 30;
        }

        $bucket = function ($s, $e, $label) use ($storeId) {
            return [
                $label,
                Order::where('store_id', $storeId)->whereBetween('created_at', [$s, $e])->count(),
                (float) Order::where('store_id', $storeId)->whereIn('status', [Order::STATUS_SELESAI, Order::STATUS_REFUND])->whereBetween('created_at', [$s, $e])->sum('grand_total'),
                (float) Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
                    ->where('orders.store_id', $storeId)->where('refunds.status', 'selesai')
                    ->whereBetween('refunds.diajukan_pada', [$s, $e])->sum('refunds.jumlah'),
                (float) Withdrawal::where('store_id', $storeId)->where('status', 'selesai')->whereBetween('diajukan_pada', [$s, $e])->sum('jumlah'),
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
                $s = $end->copy()->subDays(6)->startOfDay();
                $rows[] = $bucket($s, $end->copy()->endOfDay(), $s->translatedFormat('d') . ' — ' . $end->translatedFormat('d M'));
            }
        } else {
            $months = $period <= 90 ? 3 : 12;
            for ($i = $months - 1; $i >= 0; $i--) {
                $m = now()->subMonths($i);
                $rows[] = $bucket($m->copy()->startOfMonth(), $m->copy()->endOfMonth(), $m->translatedFormat('M Y'));
            }
        }

        $fileName = 'laporan-toko-' . now()->translatedFormat('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for Excel UTF-8
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

    public function exportExcel(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        if (! $storeId) {
            return back()->with('toast', ['message' => 'Belum ada toko untuk diekspor.', 'icon' => 'storefront']);
        }

        $period = (int) $request->input('period', 30);
        if (! in_array($period, [7, 30, 90, 365])) {
            $period = 30;
        }

        $fileName = 'laporan-toko-' . now()->translatedFormat('Y-m-d') . '.xlsx';

        return Excel::download(new OwnerLaporanExport($storeId, $period), $fileName);
    }
}
