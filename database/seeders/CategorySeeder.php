<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Pakaian Pria' => ['Kemeja', 'Kaos', 'Celana', 'Jaket & Hoodie'],
            'Pakaian Wanita' => ['Dress', 'Blouse', 'Rok', 'Atasan'],
            'Pakaian Anak' => ['Setelan Anak', 'Kaos Anak'],
            'Sepatu' => ['Sneakers', 'Sepatu Formal', 'Sandal'],
            'Tas' => ['Tas Punggung', 'Tas Selempang', 'Tote Bag'],
            'Aksesoris' => ['Topi', 'Ikat Pinggang', 'Kacamata'],
            'Muslim Fashion' => ['Hijab', 'Gamis', 'Koko'],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::updateOrCreate(
                ['nama_kategori' => $parentName, 'parent_id' => null],
                ['status' => 'aktif']
            );

            foreach ($children as $childName) {
                Category::updateOrCreate(
                    ['nama_kategori' => $childName, 'parent_id' => $parent->category_id],
                    ['status' => 'aktif']
                );
            }
        }
    }
}
