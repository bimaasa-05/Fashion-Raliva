<?php

namespace App\Support;

use App\Models\Order;
use App\Models\StockMovement;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;

class StockDeductionService
{
    /**
     * Potong stok gudang saat pesanan diterima customer.
     *
     * Alokasi FIFO per warehouse (warehouse_stock_id terkecil dulu),
     * tidak pernah membuat stok minus. Kekurangan stok tidak menggagalkan
     * penerimaan — hanya dicatat agar admin menindaklanjuti.
     *
     * @return array{deducted: int, short: array<int, array{order_item_id: int, product_variant_id: int|null, kurang: int}>}
     */
    public static function deductForOrder(Order $order): array
    {
        return DB::transaction(function () use ($order) {
            $deducted = 0;
            $short = [];

            $items = $order->items()->get();

            foreach ($items as $item) {
                $need = max(0, (int) $item->quantity);
                if ($need <= 0 || ! $item->product_variant_id) {
                    continue;
                }

                $stocks = WarehouseStock::where('product_variant_id', $item->product_variant_id)
                    ->where('jumlah_stok', '>', 0)
                    ->orderBy('warehouse_stock_id')
                    ->lockForUpdate()
                    ->get();

                foreach ($stocks as $stock) {
                    if ($need <= 0) {
                        break;
                    }

                    $take = min($need, (int) $stock->jumlah_stok);
                    if ($take <= 0) {
                        continue;
                    }

                    $affected = WarehouseStock::where('warehouse_stock_id', $stock->warehouse_stock_id)
                        ->where('jumlah_stok', '>=', $take)
                        ->decrement('jumlah_stok', $take);

                    if ($affected === 0) {
                        continue;
                    }

                    StockMovement::create([
                        'warehouse_id' => $stock->warehouse_id,
                        'product_variant_id' => $item->product_variant_id,
                        'tipe_pergerakan' => StockMovement::TIPE_KELUAR,
                        'jumlah' => $take,
                        'sumber_tipe' => StockMovement::SUMBER_ORDER_ITEM,
                        'sumber_id' => $order->order_id,
                        'alasan' => sprintf('Penjualan pesanan %s (%s).', $order->nomor_order ?? $order->order_id, $item->nama_produk_snapshot ?? '-'),
                        'dibuat_oleh' => ActivityLogger::resolveActorId(),
                    ]);

                    $need -= $take;
                    $deducted += $take;
                }

                if ($need > 0) {
                    $short[] = [
                        'order_item_id' => $item->order_item_id,
                        'product_variant_id' => $item->product_variant_id,
                        'kurang' => $need,
                    ];

                    ActivityLogger::log(
                        'stock.order.short',
                        Order::class,
                        $order->order_id,
                        null,
                        ['product_variant_id' => $item->product_variant_id, 'kurang' => $need],
                        sprintf('Stok kurang %d untuk pesanan %s (varian %d).', $need, $order->nomor_order ?? $order->order_id, $item->product_variant_id)
                    );
                }
            }

            return ['deducted' => $deducted, 'short' => $short];
        }, 5);
    }
}
