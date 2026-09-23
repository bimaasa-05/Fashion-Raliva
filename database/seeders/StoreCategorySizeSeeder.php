<?php

namespace Database\Seeders;

use App\Models\StoreCategory;
use App\Models\StoreCategorySize;
use Illuminate\Database\Seeder;

class StoreCategorySizeSeeder extends Seeder
{
    public function run(): void
    {
        $peta = [
            'Fashion & Lifestyle' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'All Size'],
            'Pakaian Wanita' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'All Size'],
            'Pakaian Pria' => ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
            'Aksesoris' => ['All Size'],
            'Sepatu' => ['38', '39', '40', '41', '42', '43', '44'],
            'Anak' => ['1-2 Tahun', '3-4 Tahun', '5-6 Tahun', '7-8 Tahun', '9-10 Tahun'],
        ];

        foreach ($peta as $nama => $ukurans) {
            $kategori = StoreCategory::firstOrCreate(
                ['nama_kategori' => $nama],
                ['status' => StoreCategory::STATUS_AKTIF]
            );

            foreach ($ukurans as $i => $label) {
                StoreCategorySize::updateOrCreate(
                    ['store_category_id' => $kategori->store_category_id, 'ukuran_label' => $label],
                    ['urutan' => $i]
                );
            }
        }
    }
}
