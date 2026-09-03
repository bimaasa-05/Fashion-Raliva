<?php

namespace Database\Seeders;

use App\Models\Checkout;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $store = Store::where('status', Store::STATUS_AKTIF)->first();

        if (! $store) {
            return;
        }

        $plan = [
            [2, 13, 9, 5, 'Bahannya lembut dan adem, cocok banget buat kerja seharian. Ukuran pas sesuai tabel!'],
            [2, 14, 10, 4, 'Kualitas bagus, jahitan rapi. Sedikit warna beda dari foto tapi tetap keren.'],
            [2, 15, 11, 5, 'Sudah belanja kedua kalinya, selalu puas. Packaging rapi dan pengiriman cepat.'],
            [2, 16, 12, 3, 'Produk sesuai deskripsi, hanya saja agak kekecilan untuk ukuran L. Overall oke.'],
            [2, 13, 13, 5, 'Rekomendasi banget! Bahannya premium dan harganya sepadan. Pasti belanja lagi.'],
            [3, 25, 9, 4, 'Potongannya pas dan jatuhnya bagus. Cocok dipadukan dengan atasan kasual.'],
            [3, 26, 10, 5, 'Celana favorit sekarang! Nyaman dipakai dan modelnya modern. Fast response seller.'],
        ];

        $counter = 0;

        foreach ($plan as [$productId, $variantId, $userId, $rating, $ulasan]) {
            $product = Product::find($productId);
            $variant = $product ? ProductVariant::find($variantId) : null;
            $user = User::find($userId);

            if (! $product || ! $variant || ! $user) {
                continue;
            }

            $harga = $variant->harga ?: $product->harga_dasar;
            $counter++;

            $checkout = Checkout::create([
                'user_id' => $userId,
                'subtotal' => $harga,
                'total_diskon' => 0,
                'total_pajak' => 0,
                'biaya_layanan' => 0,
                'total_ongkir' => 0,
                'grand_total' => $harga,
                'status' => Checkout::STATUS_SELESAI,
            ]);

            $order = Order::create([
                'checkout_id' => $checkout->checkout_id,
                'store_id' => $product->store_id,
                'nomor_order' => 'SEED-' . Str::upper(Str::random(8)),
                'subtotal' => $harga,
                'total_diskon' => 0,
                'total_pajak' => 0,
                'biaya_layanan' => 0,
                'total_ongkir' => 0,
                'grand_total' => $harga,
                'status' => Order::STATUS_SELESAI,
            ]);

            $orderItem = OrderItem::create([
                'order_id' => $order->order_id,
                'product_variant_id' => $variant->product_variant_id,
                'nama_produk_snapshot' => $product->nama_produk,
                'harga_snapshot' => $harga,
                'quantity' => 1,
                'subtotal' => $harga,
                'diskon' => 0,
                'total' => $harga,
            ]);

            $review = Review::create([
                'user_id' => $userId,
                'order_item_id' => $orderItem->order_item_id,
                'product_id' => $product->product_id,
                'store_id' => $product->store_id,
                'rating' => $rating,
                'ulasan' => $ulasan,
                'status' => Review::STATUS_AKTIF,
            ]);

            $review->created_at = now()->subDays($counter * 3);
            $review->save();
        }
    }
}
