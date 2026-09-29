<?php

namespace App\Support;

use App\Models\Order;
use App\Models\StockMovement;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;

/**
 * Mengambil kekurangan pesanan dari stok gudang.
 *
 * Dipakai dua jalur:
 * - QC simpan (partial=true): ambil semaksimal mungkin, sisa dilepas ke Gudang.
 * - Tombol manual Gudang (partial=false): tolak penuh bila ada varian yang kurang.
 *
 * Pembagian proporsional qty tiap varian (floor + sisa ke qty terbesar).
 *
 * @return array{terambil: int, sisa: int, rincian: array<int, array{item: string, diambil: int}>}
 */
class ShortfallStockAllocator
{
    public static function allocate(Order $order, int $need, array $storeIds, bool $partial): array
    {
        if ($need <= 0) {
            return ['terambil' => 0, 'sisa' => 0, 'rincian' => []];
        }

        $items = $order->items()->whereNotNull('product_variant_id')->get();
        $totalQty = (int) $items->sum('quantity');
        if ($items->isEmpty() || $totalQty <= 0) {
            throw new \RuntimeException('Item pesanan tidak valid untuk disiapkan.');
        }

        $bagi = [];
        $terbagi = 0;
        foreach ($items as $item) {
            $share = intdiv($need * (int) $item->quantity, $totalQty);
            $bagi[$item->order_item_id] = $share;
            $terbagi += $share;
        }
        $sisaBagi = $need - $terbagi;
        foreach ($items->sortByDesc('quantity') as $item) {
            if ($sisaBagi <= 0) {
                break;
            }
            $bagi[$item->order_item_id]++;
            $sisaBagi--;
        }

        return DB::transaction(function () use ($order, $items, $bagi, $need, $storeIds, $partial) {
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
                if ((int) $stocks->sum('jumlah_stok') < $butuh && ! $partial) {
                    throw new \RuntimeException(
                        sprintf('Stok %s kurang (butuh %d pcs).', $item->nama_produk_snapshot ?? 'varian', $butuh)
                    );
                }
                $stokTerkunci[$item->order_item_id] = $stocks;
            }

            $terambil = 0;
            $rincian = [];
            foreach ($items as $item) {
                $butuh = $bagi[$item->order_item_id] ?? 0;
                if ($butuh <= 0) {
                    continue;
                }
                $diambilItem = 0;
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
                    $diambilItem += $ambil;
                }
                if ($diambilItem > 0) {
                    $item->increment('qty_dari_gudang', $diambilItem);
                }
                $rincian[] = ['item' => $item->nama_produk_snapshot ?? '-', 'diambil' => $diambilItem];
                $terambil += $diambilItem;
            }

            return ['terambil' => $terambil, 'sisa' => $need - $terambil, 'rincian' => $rincian];
        });
    }
}
