<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

use App\Models\Complaint;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Wallet;
use App\Support\OwnerContext;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $store = OwnerContext::currentStore();
        $storeId = $store?->store_id;

        $paidStatuses = [
            Order::STATUS_DIBAYAR,
            Order::STATUS_DIPROSES,
            Order::STATUS_DIKIRIM,
            Order::STATUS_SELESAI,
        ];

        $penjualanHariIni = (float) Order::where('store_id', $storeId)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', $paidStatuses)
            ->sum('grand_total');

        $pesananBaru = Order::where('store_id', $storeId)
            ->whereIn('status', [Order::STATUS_PENDING_PAYMENT, Order::STATUS_DIBAYAR])
            ->count();

        $produkAktif = Product::where('store_id', $storeId)
            ->where('status', Product::STATUS_AKTIF)
            ->count();

        $produkPending = Product::where('store_id', $storeId)
            ->where('status', Product::STATUS_PENDING)
            ->count();

        $wallet = Wallet::where('store_id', $storeId)->first();
        $saldoTersedia = $wallet?->saldo_tersedia ?? 0;
        $saldoTertahan = $wallet?->saldo_tertahan ?? 0;

        $komplainTerbuka = Complaint::where('store_id', $storeId)
            ->where('status', Complaint::STATUS_OPEN)
            ->count();

        $pesananTerbaru = Order::with('checkout.user')
            ->where('store_id', $storeId)
            ->latest()
            ->take(6)
            ->get();

        $ulasanTerbaru = Review::with(['user', 'product'])
            ->where('store_id', $storeId)
            ->latest()
            ->take(3)
            ->get();

        $chart = $this->salesChart($storeId, $paidStatuses);

        return view('Owner.dashboard.index', compact(
            'store',
            'penjualanHariIni',
            'pesananBaru',
            'produkAktif',
            'produkPending',
            'saldoTersedia',
            'saldoTertahan',
            'komplainTerbuka',
            'pesananTerbaru',
            'ulasanTerbaru',
            'chart',
        ));
    }

    /**
     * Aggregated sales for the dashboard chart (real data).
     */
    protected function salesChart(?int $storeId, array $paidStatuses): array
    {
        $build = fn (int $days, string $group) => Order::query()
            ->where('store_id', $storeId)
            ->whereIn('status', $paidStatuses)
            ->whereDate('created_at', '>=', Carbon::today()->subDays($days))
            ->select(
                DB::raw("DATE(created_at) as label"),
                DB::raw('SUM(grand_total) as penjualan'),
                DB::raw('COUNT(*) as pesanan')
            )
            ->groupBy('label')
            ->orderBy('label')
            ->get()
            ->map(function ($row) use ($group) {
                return [
                    'label' => $group === 'day'
                        ? Carbon::parse($row->label)->translatedFormat('D')
                        : $row->label,
                    'penjualan' => (float) $row->penjalan,
                    'pesanan' => (int) $row->pesanan,
                ];
            })
            ->values()
            ->all();

        return [
            '7' => $build(7, 'day'),
            '30' => $build(30, 'day'),
            '90' => $build(90, 'day'),
        ];
    }
}
