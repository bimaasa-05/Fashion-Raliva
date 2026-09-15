<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\ProductionOrder;
use App\Models\QualityCheck;
use App\Models\StoreStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelaporanProduksiController extends Controller
{
    private function storeIds(): array
    {
        return StoreStaff::where('user_id', auth()->id())
            ->where('status', StoreStaff::STATUS_AKTIF)
            ->pluck('store_id')
            ->all();
    }

    public function index(Request $request)
    {
        $storeIds = $this->storeIds();

        $status = in_array($request->query('status'), ['selesai', 'dibatalkan', 'semua'], true) ? $request->query('status') : 'selesai';
        $cari = trim((string) $request->query('cari'));

        $base = ProductionOrder::with(['items.productVariant.product', 'targetWarehouse', 'requester', 'materials.material', 'qualityChecks', 'results'])
            ->when($storeIds, fn ($q) => $q->whereIn('store_id', $storeIds));

        $stats = [
            'selesai' => (clone $base)->where('status', ProductionOrder::STATUS_SELESAI)->count(),
            'dibatalkan' => (clone $base)->where('status', ProductionOrder::STATUS_DIBATALKAN)->count(),
            'unit_diminta' => (clone $base)->join('production_order_items', 'production_order_items.production_order_id', '=', 'production_orders.production_order_id')->sum('production_order_items.jumlah_diminta'),
            'unit_layak' => (clone $base)->join('quality_checks', 'quality_checks.production_order_id', '=', 'production_orders.production_order_id')->sum('quality_checks.jumlah_lulus'),
            'unit_gagal' => (clone $base)->join('quality_checks', 'quality_checks.production_order_id', '=', 'production_orders.production_order_id')->sum('quality_checks.jumlah_gagal'),
        ];

        if ($status !== 'semua') {
            $base = $base->where('status', $status);
        }

        if ($cari !== '') {
            $base = $base->where(function ($q) use ($cari) {
                $q->where('nomor_produksi', 'like', "%{$cari}%")
                    ->orWhereHas('items.productVariant.product', fn ($pq) => $pq->where('nama_produk', 'like', "%{$cari}%"));
            });
        }

        $orders = $base
            ->orderByDesc('dimulai_pada')
            ->paginate(12)
            ->withQueryString()
            ->through(function (ProductionOrder $order) {
                $diminta = $order->items->sum('jumlah_diminta');
                $layak = $order->qualityChecks->sum('jumlah_lulus');
                $gagal = $order->qualityChecks->sum('jumlah_gagal');

                $order->setAttribute('diminta_total', $diminta);
                $order->setAttribute('layak_total', $layak);
                $order->setAttribute('gagal_total', $gagal);
                $order->setAttribute(
                    'barang_rusak',
                    $order->qualityChecks
                        ->filter(fn (QualityCheck $qc) => $qc->jumlah_gagal > 0)
                        ->values()
                );

                return $order;
            });

        return view('Produksi.pelaporan-produksi.index', compact('orders', 'stats', 'status', 'cari'));
    }
}