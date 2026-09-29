<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\StoreStaff;
use App\Models\WarehouseStock;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KekuranganController extends Controller
{
    public function index()
    {
        $storeIds = $this->storeIds();

        $orders = Order::whereIn('store_id', $storeIds)
            ->where('kekurangan_gudang', '>', 0)
            ->with(['items.productVariant.product', 'checkout', 'store'])
            ->orderByDesc('tanggal_qc')
            ->orderByDesc('order_id')
            ->paginate(15);

        $totalKekurangan = (int) Order::whereIn('store_id', $storeIds)
            ->where('kekurangan_gudang', '>', 0)
            ->sum('kekurangan_gudang');

        return view('Gudang.kekurangan.index', [
            'orders' => $orders,
            'totalKekurangan' => $totalKekurangan,
        ]);
    }

    public function siapkan(Request $request, Order $order)
    {
        $storeIds = $this->storeIds();
        if (! in_array($order->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Pesanan di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        $kurang = (int) $order->kekurangan_gudang;
        if ($kurang <= 0) {
            return back()->with('toast', ['message' => 'Pesanan ini sudah tidak memiliki kekurangan.', 'icon' => 'info']);
        }

        $items = $order->items()->whereNotNull('product_variant_id')->get();
        $totalQty = (int) $items->sum('quantity');
        if ($items->isEmpty() || $totalQty <= 0) {
            return back()->with('toast', ['message' => 'Item pesanan tidak valid untuk disiapkan.', 'icon' => 'gpp_maybe']);
        }

        // Bagi kekurangan proporsional qty tiap varian (floor + sisa ke qty terbesar).
        $bagi = [];
        $terbagi = 0;
        foreach ($items as $item) {
            $share = intdiv($kurang * (int) $item->quantity, $totalQty);
            $bagi[$item->order_item_id] = $share;
            $terbagi += $share;
        }
        $sisa = $kurang - $terbagi;
        foreach ($items->sortByDesc('quantity') as $item) {
            if ($sisa <= 0) {
                break;
            }
            $bagi[$item->order_item_id]++;
            $sisa--;
        }

        try {
            DB::transaction(function () use ($order, $items, $bagi, $kurang, $storeIds) {
                // Kunci & validasi stok dulu: tolak penuh bila ada varian yang kurang.
                $stokTerkunci = [];
                foreach ($items as $item) {
                    $butuh = $bagi[$item->order_item_id] ?? 0;
                    if ($butuh <= 0) {
                        continue;
                    }
                    $stocks = WarehouseStock::where('product_variant_id', $item->product_variant_id)
                        ->where('jumlah_stok', '>', 0)
                        ->whereHas('warehouse', fn ($q) => $q->whereIn('store_id', $storeIds))
                        ->orderByDesc('jumlah_stok')
                        ->lockForUpdate()
                        ->get();
                    if ((int) $stocks->sum('jumlah_stok') < $butuh) {
                        throw new \RuntimeException(
                            sprintf('Stok %s kurang (butuh %d pcs).', $item->nama_produk_snapshot ?? 'varian', $butuh)
                        );
                    }
                    $stokTerkunci[$item->order_item_id] = $stocks;
                }

                foreach ($items as $item) {
                    $butuh = $bagi[$item->order_item_id] ?? 0;
                    if ($butuh <= 0) {
                        continue;
                    }
                    foreach ($stokTerkunci[$item->order_item_id] as $stock) {
                        if ($butuh <= 0) {
                            break;
                        }
                        $ambil = min($butuh, (int) $stock->jumlah_stok);
                        if ($ambil <= 0) {
                            continue;
                        }
                        WarehouseStock::where('warehouse_stock_id', $stock->warehouse_stock_id)
                            ->where('jumlah_stok', '>=', $ambil)
                            ->decrement('jumlah_stok', $ambil);
                        StockMovement::create([
                            'warehouse_id' => $stock->warehouse_id,
                            'product_variant_id' => $item->product_variant_id,
                            'tipe_pergerakan' => StockMovement::TIPE_KELUAR,
                            'jumlah' => $ambil,
                            'sumber_tipe' => StockMovement::SUMBER_ORDER_ITEM,
                            'sumber_id' => $order->order_id,
                            'alasan' => sprintf('Penutup kekurangan pesanan %s (%s).', $order->nomor_order ?? $order->order_id, $item->nama_produk_snapshot ?? '-'),
                            'dibuat_oleh' => ActivityLogger::resolveActorId(),
                        ]);
                        $butuh -= $ambil;
                    }
                    $item->increment('qty_dari_gudang', $bagi[$item->order_item_id]);
                }

                $totalQty = (int) $items->sum('quantity');
                $order->update([
                    'kekurangan_gudang' => 0,
                    'jumlah_berhasil' => min($totalQty, (int) ($order->jumlah_berhasil ?? 0) + $kurang),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('toast', ['message' => $e->getMessage(), 'icon' => 'gpp_maybe']);
        }

        $lama = ['kekurangan_gudang' => $kurang];
        ActivityLogger::log('gudang.kekurangan.siapkan', Order::class, $order->order_id, $lama,
            ['kekurangan_gudang' => 0],
            sprintf('Kekurangan %d pcs pesanan %s disiapkan dari stok gudang.', $kurang, $order->nomor_order));

        NotificationService::sendToRoleInStores(Role::PRODUKSI, [$order->store_id], Notification::TIPE_SISTEM,
            'Kekurangan Disiapkan dari Gudang',
            sprintf('Pesanan %s — %d pcs kekurangan sudah disiapkan Gudang. Silakan dipacking ulang.',
                $order->nomor_order, $kurang),
            ActivityLogger::resolveActorId(),
            route('produksi.pemeriksaan-kualitas'));

        NotificationService::sendToRoleInStores(Role::ADMIN, [$order->store_id], Notification::TIPE_SISTEM,
            'Kekurangan Disiapkan dari Gudang',
            sprintf('Pesanan %s — %d pcs kekurangan sudah disiapkan Gudang.', $order->nomor_order, $kurang),
            ActivityLogger::resolveActorId(),
            route('admin.pesanan', ['status' => Order::STATUS_MENUNGGU_QC]));

        return back()->with('toast', [
            'message' => "Kekurangan {$kurang} pcs untuk pesanan {$order->nomor_order} disiapkan dari gudang.",
            'icon' => 'task_alt',
        ]);
    }

    private function storeIds(): array
    {
        return StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();
    }
}
