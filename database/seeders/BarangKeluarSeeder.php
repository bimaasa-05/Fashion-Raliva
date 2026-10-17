<?php

namespace Database\Seeders;

use App\Models\StockMovement;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Database\Seeder;

class BarangKeluarSeeder extends Seeder
{
    /**
     * Contoh riwayat barang keluar manual (cermin Gudang\BarangKeluarController@store):
     * StockMovement TIPE_KELUAR + SUMBER_MANUAL, stok dikurangi secara konsisten.
     *
     * Aman di-run kapan pun: hanya menyentuh varian yang stoknya cukup
     * (sisa minimal 5) dan belum punya catatan keluar manual, tanpa menghapus data.
     */
    public function run(): void
    {
        $actorId = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Gudang'))
            ->where('status', 'aktif')
            ->orderBy('user_id')
            ->value('user_id');
        if (! $actorId) {
            $this->command?->error('Tidak ada user Gudang aktif. Seeder dibatalkan.');

            return;
        }

        $warehouseIds = Warehouse::where('status', 'aktif')->pluck('warehouse_id')->all();
        if ($warehouseIds === []) {
            $this->command?->error('Tidak ada gudang aktif. Seeder dibatalkan.');

            return;
        }

        $alasan = [
            'Penjualan offline (walk-in)',
            'Retur ke supplier',
            'Barang cacat / rusak',
            'Sampling internal toko',
            'Koreksi hasil stok opname',
        ];

        $kandidat = WarehouseStock::with('productVariant.product:product_id,nama_produk')
            ->whereIn('warehouse_id', $warehouseIds)
            ->where('jumlah_stok', '>=', 10)
            ->whereNotIn('product_variant_id', function ($q) use ($warehouseIds) {
                $q->select('product_variant_id')
                    ->from('stock_movements')
                    ->whereIn('warehouse_id', $warehouseIds)
                    ->where('tipe_pergerakan', StockMovement::TIPE_KELUAR)
                    ->where('sumber_tipe', StockMovement::SUMBER_MANUAL);
            })
            ->orderBy('warehouse_stock_id')
            ->limit(8)
            ->get();

        if ($kandidat->isEmpty()) {
            $this->command?->info('Tidak ada varian yang memenuhi syarat (stok >= 10 dan belum ada keluar manual).');

            return;
        }

        $dibuat = 0;
        foreach ($kandidat->values() as $i => $stok) {
            $jumlah = min(2 + ($stok->product_variant_id % 4), $stok->jumlah_stok - 5);
            if ($jumlah < 1) {
                continue;
            }

            StockMovement::create([
                'warehouse_id' => $stok->warehouse_id,
                'product_variant_id' => $stok->product_variant_id,
                'tipe_pergerakan' => StockMovement::TIPE_KELUAR,
                'jumlah' => $jumlah,
                'sumber_tipe' => StockMovement::SUMBER_MANUAL,
                'sumber_id' => null,
                'alasan' => $alasan[$i % count($alasan)],
                'dibuat_oleh' => $actorId,
                'created_at' => now()->subDays(10 - $i)->setTime(10 + ($i % 8), 15, 0),
            ]);
            $stok->decrement('jumlah_stok', $jumlah);
            $dibuat++;
        }

        $this->command?->info("BarangKeluarSeeder selesai: {$dibuat} catatan keluar manual.");
    }
}
