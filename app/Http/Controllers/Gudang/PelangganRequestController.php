<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\WarehouseStock;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class PelangganRequestController extends Controller
{
    use ResolvesActiveWarehouse;

    public function index(Request $request)
    {
        $warehouses = $this->assignedWarehouses();
        $warehouse = $this->activeWarehouse();

        $requests = collect();

        $stokWh = WarehouseStock::where('warehouse_id', $warehouse?->warehouse_id)
            ->pluck('jumlah_stok', 'product_variant_id');

        if ($warehouse) {
            $requests = Order::with(['store', 'items.productVariant.product'])
                ->where('store_id', $warehouse->store_id)
                ->whereIn('status', [Order::STATUS_DIBAYAR, Order::STATUS_DIPROSES])
                ->orderByDesc('created_at')
                ->get()
                ->map(function ($order) use ($stokWh) {
                    $first = $order->items->first();
                    $variant = $first?->productVariant;
                    $stokTersedia = $stokWh->has($variant?->product_variant_id)
                        ? $stokWh->get($variant?->product_variant_id) > 0
                        : false;

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

    public function konfirmasi(Request $request)
    {
        $warehouse = $this->activeWarehouse();

        if (! $warehouse) {
            return back()->with('toast', ['message' => 'Tidak ada gudang aktif.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'order_id' => 'required|exists:orders,order_id',
            'hasil' => 'required|in:tersedia,diteruskan,tidak_tersedia',
            'catatan' => 'nullable|string|max:500',
        ], [
            'order_id.required' => 'Order wajib dipilih.',
            'order_id.exists' => 'Order tidak valid.',
            'hasil.required' => 'Hasil pengecekan wajib dipilih.',
            'hasil.in' => 'Hasil pengecekan tidak valid.',
        ]);

        $order = Order::with(['items.productVariant', 'checkout.user'])->firstOrFail();

        $labelMap = [
            'tersedia' => 'Tersedia — Siap diproses',
            'diteruskan' => 'Diteruskan ke Produksi',
            'tidak_tersedia' => 'Tidak Tersedia — Bahan kosong',
        ];

        ActivityLogger::log(
            'stock.request.confirm',
            Order::class,
            $order->order_id,
            null,
            ['hasil' => $data['hasil'], 'catatan' => $data['catatan']],
            sprintf('Konfirmasi ketersediaan untuk order %s: %s.', $order->nomor_order, $labelMap[$data['hasil']] ?? $data['hasil'])
        );

        $userId = $order->checkout?->user_id;
        if ($userId) {
            Notification::create([
                'user_id' => $userId,
                'tipe' => Notification::TIPE_ORDER,
                'judul' => 'Status Ketersediaan Bahan',
                'pesan' => sprintf('Pengecekan bahan untuk order %s: %s.', $order->nomor_order, $labelMap[$data['hasil']] ?? $data['hasil']),
            ]);
        }

        return back()->with('toast', ['message' => 'Konfirmasi ketersediaan berhasil dikirim.', 'icon' => 'task_alt']);
    }
}
