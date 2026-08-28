<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\WarehouseStock;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index()
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return view('Gudang.dashboard.index', [
                'warehouses' => $warehouses,
                'warehouse' => null,
                'stats' => null,
                'lowStock' => collect(),
                'recentActivity' => collect(),
                'chart' => ['labels' => [], 'masuk' => [], 'keluar' => []],
                'statusDist' => ['aman' => 0, 'menipis' => 0, 'kritis' => 0, 'habis' => 0],
            ]);
        }

        $warehouseId = $warehouse->warehouse_id;

        // Ringkasan stok per varian di gudang aktif.
        $stocks = WarehouseStock::with(['productVariant.product.category', 'productVariant.product'])
            ->where('warehouse_id', $warehouseId)
            ->get();

        $totalProduk = $stocks->pluck('productVariant.product_id')->unique()->count();
        $totalStok = $stocks->sum('jumlah_stok');

        $statusCounts = $stocks->map(function ($s) {
            return $this->stockStatus($s);
        })->countBy()->all();

        $lowStock = $stocks->filter(fn ($s) => $s->jumlah_stok <= $s->stok_minimum)
            ->sortBy('jumlah_stok')
            ->take(8)
            ->map(function ($s) {
                return (object) [
                    'nama_produk' => $s->productVariant->product->nama_produk ?? '-',
                    'sku' => $s->productVariant->sku ?? '-',
                    'jumlah_stok' => $s->jumlah_stok,
                    'stok_minimum' => $s->stok_minimum,
                    'status' => $this->stockStatus($s),
                ];
            })->values();

        $today = Carbon::today();
        $masukHariIni = StockMovement::where('warehouse_id', $warehouseId)
            ->where('tipe_pergerakan', StockMovement::TIPE_MASUK)
            ->whereDate('created_at', $today)->sum('jumlah');
        $keluarHariIni = StockMovement::where('warehouse_id', $warehouseId)
            ->where('tipe_pergerakan', StockMovement::TIPE_KELUAR)
            ->whereDate('created_at', $today)->sum('jumlah');

        // Pergerakan 7 hari terakhir untuk chart.
        $chart = $this->buildMovementChart($warehouseId, 7);

        // Aktivitas terbaru (5 terakhir).
        $recentActivity = StockMovement::with(['productVariant.product'])
            ->where('warehouse_id', $warehouseId)
            ->orderByDesc('created_at')
            ->take(6)
            ->get()
            ->map(function ($m) {
                return (object) [
                    'tipe' => $m->tipe_pergerakan,
                    'jumlah' => $m->jumlah,
                    'nama_produk' => $m->productVariant->product->nama_produk ?? '-',
                    'alasan' => $m->alasan,
                    'created_at' => $m->created_at,
                ];
            });

        // Pelanggan request = pesanan toko ini yang menunggu pemenuhan gudang
        // (status dibayar/diproses), konsisten dengan halaman Pelanggan Request.
        $pelangganRequest = \App\Models\Order::where('store_id', $warehouse->store_id)
            ->whereIn('status', [\App\Models\Order::STATUS_DIBAYAR, \App\Models\Order::STATUS_DIPROSES])
            ->count();

        // --- Widget ringkasan real (pengganti kartu statis hardcode) ---
        // Target penerimaan harian (konstanta bisnis, bisa diatur).
        $targetHarian = 150;
        $targetPenerimaanPct = $targetHarian > 0 ? (int) min(100, round($masukHariIni / $targetHarian * 100)) : 0;

        // Akurasi stok = persentase varian stok yang masih tersedia (>0).
        $totalVarian = $stocks->count();
        $varianTersedia = $stocks->where('jumlah_stok', '>', 0)->count();
        $akurasiPct = $totalVarian > 0 ? (int) round($varianTersedia / $totalVarian * 100) : 100;

        // SLA pemenuhan = persentase pesanan menunggu yang stoknya tersedia di gudang ini.
        $orderRequests = \App\Models\Order::where('store_id', $warehouse->store_id)
            ->whereIn('status', [\App\Models\Order::STATUS_DIBAYAR, \App\Models\Order::STATUS_DIPROSES])
            ->with(['items.productVariant'])
            ->get();
        $stokWh = \App\Models\WarehouseStock::where('warehouse_id', $warehouseId)
            ->pluck('jumlah_stok', 'product_variant_id');
        $reqTersedia = 0;
        foreach ($orderRequests as $o) {
            $v = $o->items->first()?->productVariant;
            if ($v && ($stokWh->get($v->product_variant_id, 0) > 0)) {
                $reqTersedia++;
            }
        }
        $slaPct = $orderRequests->count() > 0 ? (int) round($reqTersedia / $orderRequests->count() * 100) : 100;

        // Kategori stok terbesar (top 4 by total unit).
        $kategoriTerbesar = $stocks->map(function ($s) {
            return (object) [
                'nama' => $s->productVariant->product->category->nama_kategori ?? 'Lainnya',
                'jumlah' => $s->jumlah_stok,
                'sku' => $s->productVariant->product->category_id ?? 0,
            ];
        })->groupBy('nama')->map(function ($grp) {
            return (object) [
                'nama' => $grp->first()->nama,
                'jumlah' => $grp->sum('jumlah'),
                'sku' => \App\Models\Product::where('category_id', $grp->first()->sku)->count(),
            ];
        })->sortByDesc('jumlah')->take(4)->values();

        $stats = (object) [
            'total_produk' => $totalProduk,
            'total_stok' => $totalStok,
            'masuk_hari_ini' => $masukHariIni,
            'keluar_hari_ini' => $keluarHariIni,
            'menipis' => $statusCounts['menipis'] ?? 0,
            'kritis' => $statusCounts['kritis'] ?? 0,
            'habis' => $statusCounts['habis'] ?? 0,
            'rusak' => $pelangganRequest,
        ];

        return view('Gudang.dashboard.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'stats' => $stats,
            'lowStock' => $lowStock,
            'recentActivity' => $recentActivity,
            'chart' => $chart,
            'statusDist' => [
                'aman' => $statusCounts['aman'] ?? 0,
                'menipis' => $statusCounts['menipis'] ?? 0,
                'kritis' => $statusCounts['kritis'] ?? 0,
                'habis' => $statusCounts['habis'] ?? 0,
            ],
            'targetPenerimaan' => [
                'pct' => $targetPenerimaanPct,
                'masuk' => $masukHariIni,
                'target' => $targetHarian,
            ],
            'akurasi' => [
                'pct' => $akurasiPct,
                'tersedia' => $varianTersedia,
                'total' => $totalVarian,
            ],
            'sla' => [
                'pct' => $slaPct,
                'tersedia' => $reqTersedia,
                'total' => $orderRequests->count(),
            ],
            'kategoriTerbesar' => $kategoriTerbesar,
        ]);
    }

    private function stockStatus(WarehouseStock $s): string
    {
        if ($s->jumlah_stok <= 0) {
            return 'habis';
        }
        if ($s->jumlah_stok <= $s->stok_minimum) {
            return $s->jumlah_stok <= (int) round($s->stok_minimum / 2) ? 'kritis' : 'menipis';
        }

        return 'aman';
    }

    private function buildMovementChart(int $warehouseId, int $days): array
    {
        $labels = [];
        $masuk = [];
        $keluar = [];
        $mapHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $mapHari[(int) $date->format('w')];
            $masuk[] = (int) StockMovement::where('warehouse_id', $warehouseId)
                ->whereIn('tipe_pergerakan', [StockMovement::TIPE_MASUK, StockMovement::TIPE_MUTASI_MASUK])
                ->whereDate('created_at', $date)->sum('jumlah');
            $keluar[] = (int) StockMovement::where('warehouse_id', $warehouseId)
                ->whereIn('tipe_pergerakan', [StockMovement::TIPE_KELUAR, StockMovement::TIPE_MUTASI_KELUAR])
                ->whereDate('created_at', $date)->sum('jumlah');
        }

        return ['labels' => $labels, 'masuk' => $masuk, 'keluar' => $keluar];
    }
}
