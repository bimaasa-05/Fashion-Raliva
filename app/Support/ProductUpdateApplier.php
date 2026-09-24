<?php

namespace App\Support;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductUpdateApplier
{
    public static function apply(Product $product, array $payload): void
    {
        $resetStatus = $product->status === Product::STATUS_DITOLAK;

        DB::transaction(function () use ($product, $payload, $resetStatus) {
            $recipeChanged = ! empty($payload['resep_diubah']);
            $requirements = $recipeChanged
                ? collect($payload['resep'] ?? [])->map(function ($row) use ($product) {
                    $material = ! empty($row['material_id'])
                        ? \App\Models\BahanProduksi::where('bahan_id', $row['material_id'])->where('store_id', $product->store_id)->first()
                        : null;

                    return [
                        'material_id' => $material?->bahan_id,
                        'nama_bahan' => trim((string) ($row['nama_bahan'] ?? '')),
                        'satuan' => $row['satuan'],
                        'jumlah_per_unit' => (float) ($row['jumlah_per_unit'] ?? 0),
                        'biaya_per_unit' => (float) ($row['biaya_per_unit'] ?? 0),
                    ];
                })->all()
                : $product->materialRequirements()->get()->map(fn ($row) => [
                    'material_id' => $row->material_id,
                    'nama_bahan' => $row->nama_bahan,
                    'satuan' => $row->satuan,
                    'jumlah_per_unit' => (float) $row->jumlah_per_unit,
                    'biaya_per_unit' => (float) $row->biaya_per_unit,
                ])->all();
            $target = array_key_exists('target_produksi', $payload) && $payload['target_produksi'] !== null
                ? (int) $payload['target_produksi']
                : (int) ($product->target_produksi ?? 0);
            $overhead = array_key_exists('biaya_tambahan', $payload) && $payload['biaya_tambahan'] !== null && $payload['biaya_tambahan'] !== ''
                ? (float) $payload['biaya_tambahan']
                : (float) ($product->biaya_tambahan ?? 0);
            $cost = ProductCostCalculator::calculate($requirements, $overhead, $target, (float) $payload['harga_dasar']);
            $profileChanged = $recipeChanged
                || (array_key_exists('target_produksi', $payload) && $payload['target_produksi'] !== null)
                || (array_key_exists('biaya_tambahan', $payload) && $payload['biaya_tambahan'] !== null && $payload['biaya_tambahan'] !== '');

            $product->update(array_merge([
                'nama_produk' => $payload['nama_produk'],
                'harga_dasar' => $payload['harga_dasar'],
                'category_id' => $payload['category_id'] ?? null,
                'tipe_produk' => $payload['tipe_produk'] ?? $product->tipe_produk,
                'deskripsi' => $payload['deskripsi'] ?? null,
                'status' => $resetStatus ? Product::STATUS_PENDING : $product->status,
                'alasan_penolakan' => $resetStatus ? 'Diajukan ulang setelah revisi oleh Admin.' : $product->alasan_penolakan,
            ], $profileChanged ? [
                'target_produksi' => $target > 0 ? $target : null,
                'modal_produksi' => $cost['modal_per_unit'],
                'biaya_tambahan' => $overhead,
            ] : []));

            if ($recipeChanged) {
                $product->materialRequirements()->delete();
                $product->materialRequirements()->createMany($requirements);
            }

            $hapusIds = collect($payload['hapus_foto_ids'] ?? [])
                ->map(fn ($value) => (int) $value)
                ->filter()
                ->all();
            if ($hapusIds) {
                $fotos = ProductImage::where('product_id', $product->product_id)
                    ->whereIn('product_image_id', $hapusIds)
                    ->get();
                foreach ($fotos as $foto) {
                    try {
                        Storage::disk('public')->delete($foto->file_gambar);
                    } catch (\Throwable $e) {
                    }
                    $foto->delete();
                }
            }

            $sisaSlot = 5 - ProductImage::where('product_id', $product->product_id)->count();
            $staged = collect($payload['staged_images'] ?? [])
                ->map(fn ($path) => trim((string) $path))
                ->filter()
                ->values();
            if ($staged->isNotEmpty() && $sisaSlot > 0) {
                $urutan = (int) (ProductImage::where('product_id', $product->product_id)->max('urutan') ?? -1) + 1;
                foreach ($staged as $stagedPath) {
                    if ($sisaSlot <= 0) {
                        break;
                    }
                    if (! Storage::disk('public')->exists($stagedPath)) {
                        continue;
                    }

                    $extension = strtolower(pathinfo($stagedPath, PATHINFO_EXTENSION) ?: 'jpg');
                    $target = 'products/'.$product->product_id.'-'.Str::uuid().'.'.$extension;
                    Storage::disk('public')->move($stagedPath, $target);
                    ProductImage::create([
                        'product_id' => $product->product_id,
                        'file_gambar' => $target,
                        'urutan' => $urutan++,
                    ]);
                    $sisaSlot--;
                }
            }

            $ukuranList = isset($payload['ukuran_terpilih']) && $payload['ukuran_terpilih'] !== ''
                ? array_values(array_filter(array_map('trim', explode(',', $payload['ukuran_terpilih']))))
                : [];
            $submittedColors = array_values($payload['warna'] ?? []);
            $filledColors = array_values(array_filter(array_map(fn ($warna) => trim((string) $warna), $submittedColors), fn ($warna) => $warna !== ''));
            $hasVariantRows = collect($payload['varian_stok'] ?? [])->isNotEmpty();
            $warnaList = $filledColors !== [] ? $filledColors : ($hasVariantRows ? [null] : []);
            if ($ukuranList && $warnaList !== []) {
                $warnaHexMap = collect($filledColors)->values()->mapWithKeys(function ($warna, $i) use ($payload) {
                    $hex = trim((string) ($payload['warna_hex'][$i] ?? ''));

                    return [$warna => WarnaPalet::resolve($hex, $warna) ?? ''];
                })->all();

                $perVarian = collect($payload['varian_stok'] ?? [])->keyBy(fn ($v) => trim((string) ($v['ukuran'] ?? '')).'|'.trim((string) ($v['warna'] ?? '')));
                $warehouse = Warehouse::where('store_id', $product->store_id)
                    ->where('status', Warehouse::STATUS_AKTIF)
                    ->first();
                $existing = ProductVariant::where('product_id', $product->product_id)->get()
                    ->keyBy(fn ($v) => trim((string) $v->ukuran).'|'.trim((string) $v->warna));

                foreach ($ukuranList as $uk) {
                    foreach ($warnaList as $wr) {
                        $color = $wr === null ? null : trim((string) $wr);
                        $key = trim((string) $uk).'|'.trim((string) $color);
                        $detail = $perVarian->get($key);
                        $variant = $existing->get($key);
                        if (! $variant) {
                            $variant = ProductVariant::create([
                                'product_id' => $product->product_id,
                                'sku' => strtoupper(substr($product->nama_produk, 0, 3)).'-'.str_pad($product->product_id, 4, '0').'-'.strtoupper(substr($uk, 0, 1)).($color === null ? '' : substr($color, 0, 1)).rand(10, 99),
                                'ukuran' => trim($uk),
                                'warna' => $color,
                                'warna_hex' => $color === null ? null : ($warnaHexMap[$color] ?? WarnaPalet::resolve(null, $color)),
                                'harga' => $payload['harga_dasar'],
                                'status' => 'aktif',
                            ]);
                            $existing[$key] = $variant;
                        } else {
                            $variant->update([
                                'warna_hex' => $color === null ? $variant->warna_hex : ($warnaHexMap[$color] ?? $variant->warna_hex),
                                'harga' => $payload['harga_dasar'],
                            ]);
                        }
                        if ($warehouse) {
                            WarehouseStock::updateOrCreate(
                                ['warehouse_id' => $warehouse->warehouse_id, 'product_variant_id' => $variant->product_variant_id],
                                ['jumlah_stok' => (int) ($detail['stok'] ?? 0), 'jumlah_direservasi' => 0, 'stok_minimum' => (int) ($detail['stok_minimum'] ?? 0)]
                            );
                        }
                    }
                }
            }
        });
    }
}
