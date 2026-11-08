<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Support\DemoProductCatalog;
use Illuminate\Console\Command;

class RepairDemoProducts extends Command
{
    protected $signature = 'raliva:repair-demo-products
        {--dry-run : Tampilkan rencana tanpa mengubah data}
        {--store= : Batasi perbaikan produk ke store_id tertentu}';

    protected $description = 'Perbaiki foto lokal, stok, dan warna produk demo Raliva secara idempotent';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $storeId = $this->option('store') !== null ? (int) $this->option('store') : null;

        $products = Product::query()
            ->with(['images', 'variants'])
            ->when($storeId, fn ($query) => $query->where('store_id', $storeId))
            ->orderBy('product_id')
            ->get()
            ->filter(fn (Product $product) => DemoProductCatalog::isDemoProduct($product->nama_produk))
            ->values();

        if ($products->isEmpty()) {
            $this->warn('Tidak ada produk demo yang cocok.');

            return self::SUCCESS;
        }

        $stats = [
            'products' => $products->count(),
            'images_created' => 0,
            'images_updated' => 0,
            'colors_updated' => 0,
            'hex_filled' => 0,
            'stock_created' => 0,
            'stock_updated' => 0,
            'stock_skipped' => 0,
            'orphan_deleted' => 0,
        ];

        $validVariantIds = ProductVariant::query()->pluck('product_variant_id')->all();
        $validWarehouseIds = Warehouse::query()->pluck('warehouse_id')->all();

        $orphanQuery = WarehouseStock::query()
            ->where(function ($query) use ($validVariantIds, $validWarehouseIds) {
                $query->whereNotIn('product_variant_id', $validVariantIds ?: [0])
                    ->orWhereNotIn('warehouse_id', $validWarehouseIds ?: [0]);
            });

        $stats['orphan_deleted'] = (clone $orphanQuery)->count();

        if (! $dryRun && $stats['orphan_deleted'] > 0) {
            $orphanQuery->delete();
        }

        foreach ($products as $product) {
            $imagePaths = DemoProductCatalog::imagePathsFor($product->nama_produk) ?? [];

            foreach ($imagePaths as $index => $path) {
                $urutan = $index + 1;
                $existing = $product->images->firstWhere('urutan', $urutan);

                if (! $existing) {
                    $stats['images_created']++;
                    if (! $dryRun) {
                        ProductImage::create([
                            'product_id' => $product->product_id,
                            'urutan' => $urutan,
                            'file_gambar' => $path,
                        ]);
                    }

                    continue;
                }

                if ($existing->file_gambar !== $path || str_contains((string) $existing->file_gambar, 'picsum.photos')) {
                    $stats['images_updated']++;
                    if (! $dryRun) {
                        $existing->update(['file_gambar' => $path]);
                    }
                }
            }

            $primaryWarehouse = Warehouse::query()
                ->where('store_id', $product->store_id)
                ->where('status', Warehouse::STATUS_AKTIF)
                ->orderBy('warehouse_id')
                ->first();

            foreach ($product->variants as $variant) {
                if ($variant->status !== ProductVariant::STATUS_AKTIF) {
                    continue;
                }

                $canonical = DemoProductCatalog::canonicalColor((string) $variant->warna);
                $hex = DemoProductCatalog::colorHex($canonical);

                if ($variant->warna !== $canonical) {
                    $stats['colors_updated']++;
                    if (! $dryRun) {
                        $variant->warna = $canonical;
                    }
                }

                if ($hex !== null && $variant->warna_hex !== $hex) {
                    $stats['hex_filled']++;
                    if (! $dryRun) {
                        $variant->warna_hex = $hex;
                    }
                }

                if (! $dryRun && $variant->isDirty()) {
                    $variant->save();
                }

                if (! $primaryWarehouse) {
                    $stats['stock_skipped']++;

                    continue;
                }

                $stock = WarehouseStock::query()
                    ->where('warehouse_id', $primaryWarehouse->warehouse_id)
                    ->where('product_variant_id', $variant->product_variant_id)
                    ->first();

                $target = [
                    'jumlah_stok' => DemoProductCatalog::STOCK_PER_VARIANT,
                    'jumlah_direservasi' => 0,
                    'stok_minimum' => DemoProductCatalog::STOCK_MINIMUM,
                ];

                if (! $stock) {
                    $stats['stock_created']++;
                    if (! $dryRun) {
                        WarehouseStock::create(array_merge($target, [
                            'warehouse_id' => $primaryWarehouse->warehouse_id,
                            'product_variant_id' => $variant->product_variant_id,
                        ]));
                    }

                    continue;
                }

                if ((int) $stock->jumlah_stok !== $target['jumlah_stok']
                    || (int) $stock->jumlah_direservasi !== $target['jumlah_direservasi']
                    || (int) $stock->stok_minimum !== $target['stok_minimum']) {
                    $stats['stock_updated']++;
                    if (! $dryRun) {
                        $stock->update($target);
                    }
                }
            }
        }

        $this->table(
            ['Item', 'Jumlah'],
            [
                ['Produk demo', $stats['products']],
                ['Foto dibuat', $stats['images_created']],
                ['Foto diperbarui', $stats['images_updated']],
                ['Warna dinormalisasi', $stats['colors_updated']],
                ['Hex warna diisi', $stats['hex_filled']],
                ['Stok dibuat', $stats['stock_created']],
                ['Stok diperbarui', $stats['stock_updated']],
                ['Stok dilewati (tanpa gudang)', $stats['stock_skipped']],
                ['Stok orphan dihapus', $stats['orphan_deleted']],
            ]
        );

        if ($dryRun) {
            $this->info('Dry-run selesai. Tidak ada data yang diubah.');
        } else {
            $this->info('Repair produk demo selesai.');
        }

        return self::SUCCESS;
    }
}
