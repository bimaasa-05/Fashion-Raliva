<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\ProductionMaterial;
use App\Models\ProductionOrder;
use App\Models\QualityCheck;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'diproses' => ProductionOrder::where('status', ProductionOrder::STATUS_DIPROSES)->count(),
            'menunggu_qc' => ProductionOrder::where('status', ProductionOrder::STATUS_MENUNGGU_QC)->count(),
            'selesai_bulan' => ProductionOrder::where('status', ProductionOrder::STATUS_SELESAI)->where('selesai_pada', '>=', now()->startOfMonth())->count(),
            'layak_bulan' => (int) QualityCheck::where('diperiksa_pada', '>=', now()->startOfMonth())->sum('jumlah_lulus'),
            'rusak_bulan' => (int) QualityCheck::where('diperiksa_pada', '>=', now()->startOfMonth())->sum('jumlah_gagal'),
            'bahan_menipis' => ProductionMaterial::whereColumn('stok_sekarang', '<=', 'minimal_stok')->count(),
        ];

        $prioritas = ProductionOrder::whereIn('status', [ProductionOrder::STATUS_DIPROSES, ProductionOrder::STATUS_MENUNGGU_QC])
            ->with(['items.productVariant.product', 'qualityChecks'])
            ->orderByDesc('prioritas')
            ->orderBy('dimulai_pada')
            ->take(3)
            ->get()
            ->map(function (ProductionOrder $order) {
                $diminta = $order->items->sum('jumlah_diminta');
                $layak = $order->qualityChecks->sum('jumlah_lulus');
                $pct = $diminta > 0 ? min(100, (int) round($layak / $diminta * 100)) : 0;

                return [
                    'production_order_id' => $order->production_order_id,
                    'nomor_produksi' => $order->nomor_produksi,
                    'produk' => $order->items->first()?->productVariant?->product?->nama_produk ?? 'Produk',
                    'jumlah_diminta' => $diminta,
                    'layak' => $layak,
                    'pct' => $pct,
                    'status' => $order->status,
                ];
            });

        $bahanMenipis = ProductionMaterial::whereColumn('stok_sekarang', '<=', 'minimal_stok')
            ->orderBy('stok_sekarang')
            ->first();

        $events = collect();

        QualityCheck::with('productionOrder')
            ->latest('diperiksa_pada')
            ->limit(4)
            ->get()
            ->each(function (QualityCheck $qc) use ($events) {
                $nomor = $qc->productionOrder?->nomor_produksi;
                $events->push([
                    'tipe' => 'qc',
                    'judul' => 'Pemeriksaan QC',
                    'pesan' => ($nomor ? $nomor.' — ' : '').number_format($qc->jumlah_lulus, 0, ',', '.').' unit layak'.($qc->jumlah_gagal > 0 ? ' / '.number_format($qc->jumlah_gagal, 0, ',', '.').' defect' : ''),
                    'waktu' => $qc->diperiksa_pada,
                ]);
            });

        ProductionOrder::where('status', ProductionOrder::STATUS_SELESAI)
            ->whereNotNull('selesai_pada')
            ->latest('selesai_pada')
            ->take(3)
            ->get()
            ->each(function (ProductionOrder $order) use ($events) {
                $events->push([
                    'tipe' => 'selesai',
                    'judul' => 'Produksi Selesai',
                    'pesan' => $order->nomor_produksi.' — unit layak masuk ke Gudang.',
                    'waktu' => $order->selesai_pada,
                ]);
            });

        ProductionOrder::where('status', ProductionOrder::STATUS_DIPROSES)
            ->whereNotNull('dimulai_pada')
            ->latest('dimulai_pada')
            ->take(3)
            ->get()
            ->each(function (ProductionOrder $order) use ($events) {
                $events->push([
                    'tipe' => 'proses',
                    'judul' => 'Mulai Diproses',
                    'pesan' => $order->nomor_produksi.' — masuk antrean produksi.',
                    'waktu' => $order->dimulai_pada,
                ]);
            });

        $events = $events->sortByDesc('waktu')->values()->take(6);

        return view('Produksi.dashboard.index')->with([
            'storeSuspended' => \App\Support\StoreGate::isLocked(),
            'suspendedStores' => \App\Support\StoreGate::suspendedStoreNames(),
            'stats' => $stats,
            'chart' => $this->chartData(),
            'prioritas' => $prioritas,
            'bahanMenipis' => $bahanMenipis,
            'events' => $events,
        ]);
    }

    private function chartData(): array
    {
        $range7 = ['labels' => [], 'output' => [], 'target' => []];
        $rows = QualityCheck::where('diperiksa_pada', '>=', today()->subDays(6)->startOfDay())
            ->selectRaw('DATE(diperiksa_pada) as tgl, SUM(jumlah_lulus) as total')
            ->groupBy('tgl')
            ->pluck('total', 'tgl');

        for ($i = 0; $i < 7; $i++) {
            $d = today()->subDays(6 - $i);
            $range7['labels'][] = $d->shortDayName;
            $range7['output'][] = (int) ($rows[$d->format('Y-m-d')] ?? 0);
        }
        $range7['target'] = array_fill(0, 7, 40);

        $range30 = ['labels' => [], 'output' => [], 'target' => array_fill(0, 4, 280)];
        for ($i = 0; $i < 4; $i++) {
            $ws = today()->subWeeks(3 - $i)->startOfWeek();
            $we = $ws->copy()->endOfWeek();
            $range30['labels'][] = 'Minggu '.($i + 1);
            $range30['output'][] = (int) QualityCheck::whereBetween('diperiksa_pada', [$ws, $we])->sum('jumlah_lulus');
        }

        $range90 = ['labels' => [], 'output' => [], 'target' => array_fill(0, 3, 720)];
        for ($i = 2; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $range90['labels'][] = $month->translatedFormat('M');
            $range90['output'][] = (int) QualityCheck::whereBetween('diperiksa_pada', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])->sum('jumlah_lulus');
        }

        return ['7' => $range7, '30' => $range30, '90' => $range90];
    }
}