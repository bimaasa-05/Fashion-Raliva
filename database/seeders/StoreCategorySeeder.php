<?php

namespace Database\Seeders;

use App\Models\StoreCategory;
use Illuminate\Database\Seeder;

class StoreCategorySeeder extends Seeder
{
    public function run(): void
    {
        $kategoriList = ['Fashion & Lifestyle', 'Pakaian Wanita', 'Pakaian Pria', 'Aksesoris'];

        foreach ($kategoriList as $nama) {
            StoreCategory::updateOrCreate(
                ['nama_kategori' => $nama],
                ['status' => StoreCategory::STATUS_AKTIF]
            );
        }
    }
}