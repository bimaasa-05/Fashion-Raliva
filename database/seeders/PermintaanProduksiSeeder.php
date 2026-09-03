<?php

namespace Database\Seeders;

use App\Models\ProductionOrder;
use App\Models\ProductionOrderItem;
use App\Models\Store;
use Illuminate\Database\Seeder;

class PermintaanProduksiSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();

        $stores->each(function ($store) {
            $wh = $store->warehouses()->first();
            $variant = \App\Models\ProductVariant::whereIn('product_id', \App\Models\Product::where('store_id', $store->store_id)->pluck('product_id'))->first();

            if (! $variant) {
                return;
            }

            $seed = [
                ['nomor_produksi' => 'PRD-' . str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT), 'prioritas' => 'tinggi', 'status' => 'diproses', 'jumlah' => 50, 'catatan' => 'Restok prioritas'],
                ['nomor_produksi' => 'PRD-' . str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT), 'prioritas' => 'normal', 'status' => 'selesai', 'jumlah' => 30, 'catatan' => 'Batas mingguan'],
            ];

            foreach ($seed as $s) {
                $po = ProductionOrder::create([
                    'store_id' => $store->store_id,
                    'requested_by' => $store->owner_id,
                    'assigned_to' => null,
                    'target_warehouse_id' => $wh?->warehouse_id,
                    'nomor_produksi' => $s['nomor_produksi'],
                    'prioritas' => $s['prioritas'],
                    'status' => $s['status'],
                    'catatan' => $s['catatan'],
                    'dimulai_pada' => $s['status'] === 'selesai' ? now()->subDays(10) : null,
                    'selesai_pada' => $s['status'] === 'selesai' ? now()->subDays(2) : null,
                ]);

                ProductionOrderItem::create([
                    'production_order_id' => $po->production_order_id,
                    'product_variant_id' => $variant->product_variant_id,
                    'jumlah_diminta' => $s['jumlah'],
                ]);
            }
        });
    }
}
