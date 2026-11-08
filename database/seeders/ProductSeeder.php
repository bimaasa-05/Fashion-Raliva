<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Store;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Support\DemoProductCatalog;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $store = Store::where('status', Store::STATUS_AKTIF)->first();

        if (! $store) {
            $this->command->warn('Tidak ada toko aktif. Produk tidak dibuat.');

            return;
        }

        $cat = fn(string $name) => Category::where('nama_kategori', $name)->whereNotNull('parent_id')->value('category_id');

        $warehouse = Warehouse::where('store_id', $store->store_id)
            ->where('status', Warehouse::STATUS_AKTIF)
            ->first();

        if (! $warehouse) {
            $this->command->warn('Tidak ada gudang aktif. Stok produk tidak dibuat.');
        }

        $products = [
            [
                'nama_produk' => 'Oversized Linen Shirt',
                'deskripsi' => 'An effortlessly chic oversized shirt crafted from premium, breathable linen. Featuring a classic collar, button-down front, and dropped shoulders for a relaxed silhouette.',
                'harga_dasar' => 289000,
                'category' => $cat('Blouse'),
                'warna' => ['Putih', 'Hitam', 'Beige'],
                'ukuran' => ['S', 'M', 'L', 'XL'],
            ],
            [
                'nama_produk' => 'Straight Fit Pants',
                'deskripsi' => 'High-end minimal straight fit trousers in a sophisticated ivory tone. Clean lines with a premium drape for a refined everyday look.',
                'harga_dasar' => 329000,
                'category' => $cat('Celana'),
                'warna' => ['Ivory', 'Hitam'],
                'ukuran' => ['S', 'M', 'L'],
            ],
            [
                'nama_produk' => 'Relaxed Blazer',
                'deskripsi' => 'A relaxed fit blazer in a muted earthy tone. Diffused tailoring with a soft drape for a modern, sophisticated silhouette.',
                'harga_dasar' => 579000,
                'category' => $cat('Kemeja'),
                'warna' => ['Muted Sand', 'Charcoal'],
                'ukuran' => ['S', 'M', 'L'],
            ],
            [
                'nama_produk' => 'Pleated Midi Skirt',
                'deskripsi' => 'A striking pleated midi skirt in motion, capturing the elegant flow of the fabric in a soft, warm neutral. Refined and distinctly high-fashion.',
                'harga_dasar' => 380000,
                'category' => $cat('Rok'),
                'warna' => ['Warm Sand', 'Hitam'],
                'ukuran' => ['S', 'M', 'L', 'XL'],
            ],
            [
                'nama_produk' => 'Linen Blend Shirt',
                'deskripsi' => 'A crisp linen blend shirt in pristine white. Natural light and breathable texture create a serene, luxury everyday aesthetic.',
                'harga_dasar' => 299000,
                'category' => $cat('Kemeja'),
                'warna' => ['Putih'],
                'ukuran' => ['S', 'M', 'L'],
            ],
            [
                'nama_produk' => 'Signature Tote Bag',
                'deskripsi' => 'An essential structured tote in premium vegan leather. Clean lines and a spacious interior for effortless everyday carry.',
                'harga_dasar' => 450000,
                'category' => $cat('Tote Bag'),
                'warna' => ['Ivory', 'Taupe'],
                'ukuran' => ['One Size'],
            ],
            [
                'nama_produk' => 'Minimalist Cap',
                'deskripsi' => 'A clean, unstructured cap in soft cotton twill. Subtle embroidered brand mark and an adjustable strap for a perfect fit.',
                'harga_dasar' => 145000,
                'category' => $cat('Topi'),
                'warna' => ['Hitam', 'Sand'],
                'ukuran' => ['One Size'],
            ],
            [
                'nama_produk' => 'Silk Blend Dress',
                'deskripsi' => 'A flowing silk blend dress designed for the contemporary woman. Soft, fluid drape and a graceful silhouette for day to evening.',
                'harga_dasar' => 645000,
                'category' => $cat('Dress'),
                'warna' => ['Blush', 'Hitam'],
                'ukuran' => ['S', 'M', 'L'],
            ],
        ];

        foreach ($products as $data) {
            if (! $data['category']) {
                continue;
            }

            $product = Product::updateOrCreate(
                ['nama_produk' => $data['nama_produk'], 'store_id' => $store->store_id],
                [
                    'category_id' => $data['category'],
                    'deskripsi' => $data['deskripsi'],
                    'harga_dasar' => $data['harga_dasar'],
                    'tipe_produk' => Product::TIPE_REGULAR,
                    'status' => Product::STATUS_AKTIF,
                ]
            );

            $variantCounter = 0;
            foreach ($data['warna'] as $warna) {
                $warna = DemoProductCatalog::canonicalColor($warna);
                foreach ($data['ukuran'] as $ukuran) {
                    $variantCounter++;
                    $variant = ProductVariant::updateOrCreate(
                        [
                            'product_id' => $product->product_id,
                            'warna' => $warna,
                            'ukuran' => $ukuran,
                        ],
                        [
                            'sku' => 'RLV-' . str_pad((string) $product->product_id, 4, '0', STR_PAD_LEFT) . '-' . str_pad((string) $variantCounter, 2, '0', STR_PAD_LEFT),
                            'warna_hex' => DemoProductCatalog::colorHex($warna),
                            'harga' => $data['harga_dasar'],
                            'status' => ProductVariant::STATUS_AKTIF,
                        ]
                    );

                    if ($warehouse) {
                        WarehouseStock::updateOrCreate(
                            ['warehouse_id' => $warehouse->warehouse_id, 'product_variant_id' => $variant->product_variant_id],
                            [
                                'jumlah_stok' => DemoProductCatalog::STOCK_PER_VARIANT,
                                'jumlah_direservasi' => 0,
                                'stok_minimum' => DemoProductCatalog::STOCK_MINIMUM,
                            ]
                        );
                    }
                }
            }

            foreach (DemoProductCatalog::imagePathsFor($data['nama_produk']) ?? [] as $order => $url) {
                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->product_id,
                        'urutan' => $order + 1,
                    ],
                    ['file_gambar' => $url]
                );
            }
        }

        $this->command->info('ProductSeeder selesai: ' . count($products) . ' produk dibuat.');
    }
}
