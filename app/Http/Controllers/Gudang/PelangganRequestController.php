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

                    $hasil = $order->status_ketersediaan;

                    if ($hasil) {
                        $statusKey = $hasil;
                        $statusLabel = match ($hasil) {
                            'tersedia' => 'Tersedia',
                            'diteruskan' => 'Diteruskan',
                            'tidak_tersedia' => 'Tidak Tersedia',
                            default => 'Sudah Dicek',
                        };
                    } elseif (! $stokTersedia) {
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
                        'status_ketersediaan' => $order->status_ketersediaan,
                        'catatan_gudang' => $order->catatan_gudang,
                        'pelanggan' => $order->checkout?->user?->nama_lengkap ?? 'Pelanggan',
                        'produk' => $variant?->product?->nama_produk ?? ($first?->nama_produk_snapshot ?? '-'),
                        'variant' => $variant,
                        'bahan' => $variant && $variant->warna
                            ? ($variant->warna.($variant->ukuran ? ' / '.$variant->ukuran : ''))
                            : '—',
                        'stok' => $variant ? (int) ($stokWh->get($variant->product_variant_id, 0)) : 0,
                        'hpp' => $variant?->harga ?? $first?->harga_snapshot ?? 0,
                        'total' => $order->grand_total,
                        'status_key' => $statusKey,
                        'status_label' => $statusLabel,
                    ];
                });

            $counts = [
                'menunggu' => $requests->whereNull('status_ketersediaan')->count(),
                'tersedia' => $requests->where('status_key', 'tersedia')->count(),
                'kosong' => $requests->whereIn('status_key', ['kosong', 'tidak_tersedia'])->count(),
                'diteruskan' => $requests->where('status_key', 'diteruskan')->count(),
                'total' => $requests->count(),
            ];
        }

        return view('Gudang.pelanggan-request.index', [
            'warehouses' => $warehouses,
            'warehouse' => $warehouse,
            'requests' => $requests,
            'counts' => $counts ?? ['menunggu' => 0, 'tersedia' => 0, 'kosong' => 0, 'diteruskan' => 0, 'total' => 0],
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

        $order = Order::where('order_id', $data['order_id'])->with(['items.productVariant', 'checkout.user'])->firstOrFail();

        $labelMap = [
            'tersedia' => 'Tersedia — Siap diproses',
            'diteruskan' => 'Diteruskan ke Produksi',
            'tidak_tersedia' => 'Tidak Tersedia — Bahan kosong',
        ];

        $order->update([
            'status_ketersediaan' => $data['hasil'],
            'catatan_gudang' => $data['catatan'] ?? null,
            'dicek_gudang_pada' => now(),
        ]);

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
