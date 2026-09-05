<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;

use App\Models\Complaint;
use App\Models\Order;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\ProductionOrder;
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

        $rating = $store ? (float) Review::where('store_id', $store->store_id)->avg('rating') : 0;
        $ratingCount = $store ? Review::where('store_id', $store->store_id)->count() : 0;

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

        $aktivitas = $this->buildAktivitas($storeId);

        return view('Owner.dashboard.index', compact(
            'store',
            'penjualanHariIni',
            'pesananBaru',
            'produkAktif',
            'produkPending',
            'saldoTersedia',
            'saldoTertahan',
            'komplainTerbuka',
            'rating',
            'ratingCount',
            'pesananTerbaru',
            'ulasanTerbaru',
            'chart',
            'aktivitas',
        ));
    }

    /**
     * Aktivitas berjalan: pesanan diproses, pengiriman, produksi, promo aktif.
     * Diambil dari data nyata (bukan statis).
     */
    protected function buildAktivitas(?int $storeId): array
    {
        $items = [];

        // Pesanan diproses
        $diproses = Order::where('store_id', $storeId)
            ->where('status', Order::STATUS_DIPROSES)
            ->latest()
            ->first();
        if ($diproses) {
            $items[] = [
                'icon' => 'precision_manufacturing',
                'title' => 'Pesanan #' . ($diproses->nomor_order ?? $diproses->order_id) . ' diproses',
                'subtitle' => 'Sedang disiapkan di gudang',
                'progress' => 50,
            ];
        }

        // Pengiriman (dikirim)
        $dikirim = Order::where('store_id', $storeId)
            ->where('status', Order::STATUS_DIKIRIM)
            ->latest()
            ->first();
        if ($dikirim) {
            $items[] = [
                'icon' => 'local_shipping',
                'title' => 'Pengiriman #' . ($dikirim->nomor_order ?? $dikirim->order_id),
                'subtitle' => 'Dalam perjalanan ke pelanggan',
                'progress' => 85,
            ];
        }

        // Produksi toko ini
        $produksi = ProductionOrder::where('store_id', $storeId)
            ->where('status', '!=', 'selesai')
            ->latest()
            ->first();
        if ($produksi) {
            $p = match ($produksi->status) {
                'diproses' => 55,
                'pending', 'menunggu' => 15,
                'selesai' => 100,
                default => 30,
            };
            $items[] = [
                'icon' => 'inventory_2',
                'title' => 'Produksi ' . ($produksi->nomor_produksi ?? 'PRQ-' . $produksi->production_order_id),
                'subtitle' => 'Sedang berjalan',
                'progress' => $p,
            ];
        }

        // Promo aktif
        $promo = Promotion::where('store_id', $storeId)
            ->where('status', 'aktif')
            ->where('berakhir_pada', '>=', Carbon::today())
            ->latest()
            ->first();
        if ($promo) {
            $sisa = Carbon::today()->diffInDays($promo->berakhir_pada, false);
            $items[] = [
                'icon' => 'local_offer',
                'title' => 'Promo ' . $promo->nama_promo . ' berjalan',
                'subtitle' => 'Diskon ' . $promo->nilai_diskon . '% — sisa ' . max(0, $sisa) . ' hari',
                'progress' => 56,
            ];
        }

        return $items;
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
                    'penjualan' => (float) $row->penjualan,
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
