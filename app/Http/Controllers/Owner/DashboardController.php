<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\Promotion;
use App\Models\Review;
use App\Models\Wallet;
use App\Services\KaryawanReportService;
use App\Support\OwnerContext;
use App\Support\SlotService;
use App\Support\StoreGate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        $followersCount = $store ? $store->followers()->count() : 0;

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

        $roi = null;
        if ($storeId) {
            $roi = (new KaryawanReportService)->ringkasanKeuangan([$storeId])['roi'] ?? null;
        }

        $totalSlot = $storeId ? SlotService::totalQuota($storeId) : 0;

        return view('Owner.dashboard.index', compact(
            'store',
            'penjualanHariIni',
            'pesananBaru',
            'produkAktif',
            'produkPending',
            'totalSlot',
            'saldoTersedia',
            'saldoTertahan',
            'komplainTerbuka',
            'rating',
            'ratingCount',
            'followersCount',
            'pesananTerbaru',
            'ulasanTerbaru',
            'chart',
            'aktivitas',
            'roi',
        ))->with([
            'storeSuspended' => StoreGate::isLocked(),
            'suspendedStores' => StoreGate::suspendedStoreNames(),
        ]);
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
                'title' => 'Pesanan #'.($diproses->nomor_order ?? $diproses->order_id).' diproses',
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
                'title' => 'Pengiriman #'.($dikirim->nomor_order ?? $dikirim->order_id),
                'subtitle' => 'Dalam perjalanan ke pelanggan',
                'progress' => 85,
            ];
        }

        // Produksi toko ini
        $produksi = ProductionOrder::where('store_id', $storeId)
            ->whereNotIn('status', [
                ProductionOrder::STATUS_SELESAI,
                ProductionOrder::STATUS_DIBATALKAN,
            ])
            ->latest()
            ->first();
        if ($produksi) {
            $p = match ($produksi->status) {
                ProductionOrder::STATUS_DIPROSES => 55,
                ProductionOrder::STATUS_REQUESTED => 15,
                ProductionOrder::STATUS_MENUNGGU_QC => 80,
                default => 30,
            };

            $subtitle = match ($produksi->status) {
                ProductionOrder::STATUS_REQUESTED => 'Menunggu persetujuan',
                ProductionOrder::STATUS_MENUNGGU_QC => 'Menunggu pemeriksaan QC',
                default => 'Sedang berjalan',
            };

            $items[] = [
                'icon' => 'inventory_2',
                'title' => 'Produksi '.($produksi->nomor_produksi ?? 'PRQ-'.$produksi->production_order_id),
                'subtitle' => $subtitle,
                'progress' => $p,
            ];
        }

        // Promo aktif
        $promo = Promotion::where('store_id', $storeId)
            ->where('status', Promotion::STATUS_AKTIF)
            ->where('mulai_pada', '<=', Carbon::today())
            ->where('berakhir_pada', '>=', Carbon::today())
            ->latest()
            ->first();
        if ($promo) {
            $sisa = Carbon::today()->diffInDays($promo->berakhir_pada, false);

            $diskon = $promo->tipe_diskon === Promotion::TIPE_PORSENTE
                ? $promo->nilai_diskon . '%'
                : 'Rp ' . number_format((float) $promo->nilai_diskon, 0, ',', '.');

            $totalHari = max(1, $promo->mulai_pada->diffInDays($promo->berakhir_pada));
            $berjalan = max(0, $promo->mulai_pada->diffInDays(Carbon::today()));
            $progress = (int) min(100, max(0, round($berjalan / $totalHari * 100)));

            $items[] = [
                'icon' => 'local_offer',
                'title' => 'Promo '.$promo->nama_promo.' berjalan',
                'subtitle' => 'Diskon '.$diskon.' — sisa '.max(0, $sisa).' hari',
                'progress' => $progress,
            ];
        }

        return $items;
    }

    /**
     * Aggregated sales for the dashboard chart (real data).
     */
    protected function salesChart(?int $storeId, array $paidStatuses): array
    {
        $build = function (int $days) use ($storeId, $paidStatuses) {
            $start = Carbon::today()->subDays($days - 1);
            $rows = Order::query()
                ->where('store_id', $storeId)
                ->whereIn('status', $paidStatuses)
                ->whereDate('created_at', '>=', $start)
                ->select(
                    DB::raw('DATE(created_at) as label'),
                    DB::raw('SUM(grand_total) as penjualan'),
                    DB::raw('COUNT(*) as pesanan')
                )
                ->groupBy('label')
                ->orderBy('label')
                ->get()
                ->keyBy('label');

            $labels = [];
            $penjualan = [];
            $pesanan = [];
            for ($i = 0; $i < $days; $i++) {
                $date = $start->copy()->addDays($i);
                $row = $rows->get($date->toDateString());
                $labels[] = $date->translatedFormat('d M');
                $penjualan[] = (float) ($row->penjualan ?? 0);
                $pesanan[] = (int) ($row->pesanan ?? 0);
            }

            return ['labels' => $labels, 'penjualan' => $penjualan, 'pesanan' => $pesanan];
        };

        return [
            '7' => $build(7),
            '30' => $build(30),
            '90' => $build(90),
        ];
    }
}
