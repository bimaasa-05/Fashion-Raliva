<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Oversized Linen Shirt (25): S/M/L/XL
        DB::table('product_variants')->where('product_variant_id', 71)->update(['harga' => 279000]); // S
        DB::table('product_variants')->where('product_variant_id', 72)->update(['harga' => 289000]); // M
        DB::table('product_variants')->where('product_variant_id', 73)->update(['harga' => 299000]); // L
        DB::table('product_variants')->where('product_variant_id', 74)->update(['harga' => 309000]); // XL

        // Straight Fit Pants (26): 28/30/32/34
        DB::table('product_variants')->where('product_variant_id', 75)->update(['harga' => 319000]); // 28
        DB::table('product_variants')->where('product_variant_id', 76)->update(['harga' => 329000]); // 30
        DB::table('product_variants')->where('product_variant_id', 77)->update(['harga' => 339000]); // 32
        DB::table('product_variants')->where('product_variant_id', 78)->update(['harga' => 349000]); // 34

        // Relaxed Blazer (27): M/L
        DB::table('product_variants')->where('product_variant_id', 79)->update(['harga' => 539000]); // M
        DB::table('product_variants')->where('product_variant_id', 80)->update(['harga' => 559000]); // L

        // Knit Cardigan Rajut (28): S/M/L
        DB::table('product_variants')->where('product_variant_id', 81)->update(['harga' => 289000]); // S
        DB::table('product_variants')->where('product_variant_id', 82)->update(['harga' => 299000]); // M
        DB::table('product_variants')->where('product_variant_id', 83)->update(['harga' => 309000]); // L

        // Midi Dress Linen (29): S/M/L
        DB::table('product_variants')->where('product_variant_id', 84)->update(['harga' => 379000]); // S
        DB::table('product_variants')->where('product_variant_id', 85)->update(['harga' => 389000]); // M
        DB::table('product_variants')->where('product_variant_id', 86)->update(['harga' => 399000]); // L

        // Basic T-Shirt Cotton (30): S/M/L/XL
        DB::table('product_variants')->where('product_variant_id', 87)->update(['harga' => 89000]); // S
        DB::table('product_variants')->where('product_variant_id', 88)->update(['harga' => 99000]); // M
        DB::table('product_variants')->where('product_variant_id', 89)->update(['harga' => 109000]); // L
        DB::table('product_variants')->where('product_variant_id', 90)->update(['harga' => 119000]); // XL

        // Denim Jacket Classic (31): M/L/XL
        DB::table('product_variants')->where('product_variant_id', 91)->update(['harga' => 449000]); // M
        DB::table('product_variants')->where('product_variant_id', 92)->update(['harga' => 459000]); // L
        DB::table('product_variants')->where('product_variant_id', 93)->update(['harga' => 469000]); // XL

        // Pleated Skirt (32): S/M/L
        DB::table('product_variants')->where('product_variant_id', 94)->update(['harga' => 265000]); // S
        DB::table('product_variants')->where('product_variant_id', 95)->update(['harga' => 275000]); // M
        DB::table('product_variants')->where('product_variant_id', 96)->update(['harga' => 285000]); // L

        // Wide Leg Trousers (33): 28/30/32
        DB::table('product_variants')->where('product_variant_id', 97)->update(['harga' => 285000]); // 28
        DB::table('product_variants')->where('product_variant_id', 98)->update(['harga' => 295000]); // 30
        DB::table('product_variants')->where('product_variant_id', 99)->update(['harga' => 305000]); // 32

        // Hoodie Fleece Premium (34): M/L/XL
        DB::table('product_variants')->where('product_variant_id', 100)->update(['harga' => 349000]); // M
        DB::table('product_variants')->where('product_variant_id', 101)->update(['harga' => 359000]); // L
        DB::table('product_variants')->where('product_variant_id', 102)->update(['harga' => 369000]); // XL

        // Silk Scarf (35): One Size (tidak diubah)
        // Leather Belt (36): 85-105cm / 90-110cm (ukuran pinggang, lebih besar = lebih mahal)
        DB::table('product_variants')->where('product_variant_id', 104)->update(['harga' => 189000]); // 85-105 cm
        DB::table('product_variants')->where('product_variant_id', 105)->update(['harga' => 199000]); // 90-110 cm
    }

    public function down(): void
    {
        // Kembalikan semua ke harga dasar produk masing-masing
        $products = [
            25 => 289000, 26 => 329000, 27 => 549000, 28 => 299000,
            29 => 389000, 30 => 99000, 31 => 459000, 32 => 275000,
            33 => 295000, 34 => 359000, 35 => 185000, 36 => 199000,
        ];

        foreach ($products as $productId => $harga) {
            DB::table('product_variants')
                ->where('product_id', $productId)
                ->update(['harga' => $harga]);
        }
    }
};
