<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Withdrawal;
use App\Models\OrderItem;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $pendapatan = (float) Order::where('store_id', $storeId)->where('status', 'selesai')->sum('grand_total');
        $pesananSelesai = Order::where('store_id', $storeId)->where('status', 'selesai')->count();

        $refund = (float) Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
            ->where('orders.store_id', $storeId)
            ->where('refunds.status', 'selesai')
            ->sum('refunds.jumlah');

        $dicairkan = (float) Withdrawal::where('store_id', $storeId)
            ->where('status', 'selesai')
            ->sum('jumlah');

        // chart data per range
        $buildRange = function ($days) use ($storeId) {
            $start = now()->subDays($days - 1)->startOfDay();
            $orders = Order::where('store_id', $storeId)
                ->where('status', 'selesai')
                ->where('created_at', '>=', $start)
                ->selectRaw('DATE(created_at) as tgl, SUM(grand_total) as pendapatan')
                ->groupBy('tgl')->pluck('pendapatan', 'tgl');

            if ($days <= 30) {
                $labels = []; $pend = []; $ref = [];
                for ($i = $days - 1; $i >= 0; $i -= 7) {
                    $end = now()->subDays($i);
                    $startW = $end->copy()->subDays(6)->startOfDay();
                    $labels[] = $startW->translatedFormat('d M');
                    $pend[] = (float) Order::where('store_id', $storeId)->where('status', 'selesai')
                        ->whereBetween('created_at', [$startW, $end->endOfDay()])->sum('grand_total');
                    $ref[] = (float) Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
                        ->where('orders.store_id', $storeId)->where('refunds.status', 'selesai')
                        ->whereBetween('refunds.diajukan_pada', [$startW, $end->endOfDay()])->sum('refunds.jumlah');
                }
                return ['labels' => $labels, 'pendapatan' => $pend, 'refund' => $ref];
            }
            $group = $days <= 90 ? 'MONTH' : 'MONTH';
            $agg = Order::where('store_id', $storeId)->where('status', 'selesai')
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

        // weekly table (last 4 weeks)
        $weekly = [];
        for ($i = 21; $i >= 0; $i -= 7) {
            $end = now()->subDays($i);
            $startW = $end->copy()->subDays(6)->startOfDay();
            $weekly[] = [
                'periode' => $startW->translatedFormat('d') . ' — ' . $end->translatedFormat('d M'),
                'pesanan' => Order::where('store_id', $storeId)->whereBetween('created_at', [$startW, $end->endOfDay()])->count(),
                'pendapatan' => (float) Order::where('store_id', $storeId)->where('status', 'selesai')->whereBetween('created_at', [$startW, $end->endOfDay()])->sum('grand_total'),
                'refund' => (float) Refund::join('orders', 'orders.order_id', '=', 'refunds.order_id')
                    ->where('orders.store_id', $storeId)->where('refunds.status', 'selesai')
                    ->whereBetween('refunds.diajukan_pada', [$startW, $end->endOfDay()])->sum('refunds.jumlah'),
            ];
        }

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
            ->with('variant.product')
            ->get()
            ->map(function ($oi) {
                return [
                    'nama' => $oi->variant?->product?->nama_produk ?? '-',
                    'terjual' => (int) $oi->terjual,
                ];
            })->all();

        return view('Owner.laporan.index', compact(
            'pendapatan', 'pesananSelesai', 'refund', 'dicairkan', 'chartData', 'top', 'weekly'
        ));
    }
}
