<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelangganRequestController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        // Customer request terkait toko gudang ini: pesanan dengan status
        // menunggu pemenuhan dari gudang (cek stok/bahan). Untuk V1 ditampilkan
        // sebagai daftar pesanan toko yang butuh pengecekan stok.
        $requests = collect();

        // Klasifikasi request berdasarkan keberadaan stok di gudang aktif.
        $stokWh = \App\Models\WarehouseStock::where('warehouse_id', $warehouse?->warehouse_id)
            ->pluck('jumlah_stok', 'product_variant_id');

        if ($warehouse) {
            $requests = \App\Models\Order::with(['store', 'items.productVariant.product'])
                ->where('store_id', $warehouse->store_id)
                ->whereIn('status', [\App\Models\Order::STATUS_DIBAYAR, \App\Models\Order::STATUS_DIPROSES])
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($order) use ($stokWh) {
                    $first = $order->items->first();
                    $variant = $first?->productVariant;
                    $stokTersedia = $stokWh->has($variant?->product_variant_id)
                        ? $stokWh->get($variant?->product_variant_id) > 0
                        : false;

                    // Status pengecekan gudang (bukan status pesanan).
                    if (! $stokTersedia) {
                        $statusKey = 'kosong';
                        $statusLabel = 'Tidak Tersedia';
                    } else {
                        $statusKey = 'tersedia';
                        $statusLabel = 'Tersedia';
                    }

                    return (object) [
                        'order_id' => $order->order_id,
                        'nomor_order' => $order->nomor_order,
                        'created_at' => $order->created_at,
                        'status_pesanan' => $order->status,
                        'pelanggan' => $order->checkout?->user?->nama_lengkap ?? 'Pelanggan',
                        'produk' => $variant?->product?->nama_produk ?? ($first?->nama_produk_snapshot ?? '-'),
                        'variant' => $variant,
                        'hpp' => $variant?->harga ?? $first?->harga_snapshot ?? 0,
                        'total' => $order->grand_total,
                        'status_key' => $statusKey,
                        'status_label' => $statusLabel,
                    ];
                });

            $counts = [
                'menunggu' => $requests->count(),
                'tersedia' => $requests->where('status_key', 'tersedia')->count(),
                'kosong' => $requests->where('status_key', 'kosong')->count(),
                'total' => $requests->count(),
            ];
        }

        return view('Gudang.pelanggan-request.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'requests' => $requests,
            'counts' => $counts ?? ['menunggu' => 0, 'tersedia' => 0, 'kosong' => 0, 'total' => 0],
            'firstCheck' => $requests->firstWhere('status_key', '!=', 'menunggu'),
        ]);
    }
}
