<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Store;
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

        $products = [
            [
                'nama_produk' => 'Oversized Linen Shirt',
                'deskripsi' => 'An effortlessly chic oversized shirt crafted from premium, breathable linen. Featuring a classic collar, button-down front, and dropped shoulders for a relaxed silhouette.',
                'harga_dasar' => 289000,
                'category' => $cat('Blouse'),
                'warna' => ['White', 'Black', 'Beige'],
                'ukuran' => ['S', 'M', 'L', 'XL'],
                'images' => ['assets/seeders/1.jfif', 'assets/seeders/2.jfif'],
            ],
            [
                'nama_produk' => 'Straight Fit Pants',
                'deskripsi' => 'High-end minimal straight fit trousers in a sophisticated ivory tone. Clean lines with a premium drape for a refined everyday look.',
                'harga_dasar' => 329000,
                'category' => $cat('Celana'),
                'warna' => ['Ivory', 'Black'],
                'ukuran' => ['S', 'M', 'L'],
                'images' => ['assets/seeders/3.jfif', 'assets/seeders/4.jfif'],
            ],
            [
                'nama_produk' => 'Relaxed Blazer',
                'deskripsi' => 'A relaxed fit blazer in a muted earthy tone. Diffused tailoring with a soft drape for a modern, sophisticated silhouette.',
                'harga_dasar' => 579000,
                'category' => $cat('Kemeja'),
                'warna' => ['Muted Sand', 'Charcoal'],
                'ukuran' => ['S', 'M', 'L'],
                'images' => ['assets/seeders/5.jfif', 'assets/seeders/6.jfif'],
            ],
            [
                'nama_produk' => 'Pleated Midi Skirt',
                'deskripsi' => 'A striking pleated midi skirt in motion, capturing the elegant flow of the fabric in a soft, warm neutral. Refined and distinctly high-fashion.',
                'harga_dasar' => 380000,
                'category' => $cat('Rok'),
                'warna' => ['Warm Sand', 'Black'],
                'ukuran' => ['S', 'M', 'L', 'XL'],
                'images' => ['assets/seeders/7.jfif', 'assets/seeders/8.jfif'],
            ],
            [
                'nama_produk' => 'Linen Blend Shirt',
                'deskripsi' => 'A crisp linen blend shirt in pristine white. Natural light and breathable texture create a serene, luxury everyday aesthetic.',
                'harga_dasar' => 299000,
                'category' => $cat('Kemeja'),
                'warna' => ['White'],
                'ukuran' => ['S', 'M', 'L'],
                'images' => ['assets/seeders/9.jfif', 'assets/seeders/10.jfif'],
            ],
            [
                'nama_produk' => 'Signature Tote Bag',
                'deskripsi' => 'An essential structured tote in premium vegan leather. Clean lines and a spacious interior for effortless everyday carry.',
                'harga_dasar' => 450000,
                'category' => $cat('Tote Bag'),
                'warna' => ['Ivory', 'Taupe'],
                'ukuran' => ['One Size'],
                'images' => ['assets/seeders/11.jfif', 'assets/seeders/12.jfif'],
            ],
            [
                'nama_produk' => 'Minimalist Cap',
                'deskripsi' => 'A clean, unstructured cap in soft cotton twill. Subtle embroidered brand mark and an adjustable strap for a perfect fit.',
                'harga_dasar' => 145000,
                'category' => $cat('Topi'),
                'warna' => ['Black', 'Sand'],
                'ukuran' => ['One Size'],
                'images' => ['assets/seeders/13.jfif', 'assets/seeders/14.jfif'],
            ],
            [
                'nama_produk' => 'Silk Blend Dress',
                'deskripsi' => 'A flowing silk blend dress designed for the contemporary woman. Soft, fluid drape and a graceful silhouette for day to evening.',
                'harga_dasar' => 645000,
                'category' => $cat('Dress'),
                'warna' => ['Blush', 'Black'],
                'ukuran' => ['S', 'M', 'L'],
                'images' => ['assets/seeders/15.jfif', 'assets/seeders/16.jfif'],
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
                foreach ($data['ukuran'] as $ukuran) {
                    $variantCounter++;
                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $product->product_id,
                            'warna' => $warna,
                            'ukuran' => $ukuran,
                        ],
                        [
                            'sku' => 'RLV-' . str_pad((string) $product->product_id, 4, '0', STR_PAD_LEFT) . '-' . str_pad((string) $variantCounter, 2, '0', STR_PAD_LEFT),
                            'harga' => $data['harga_dasar'],
                            'status' => ProductVariant::STATUS_AKTIF,
                        ]
                    );
                }
            }

            foreach ($data['images'] as $order => $url) {
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
