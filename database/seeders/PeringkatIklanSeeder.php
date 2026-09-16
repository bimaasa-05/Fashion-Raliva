<?php

namespace Database\Seeders;

use App\Models\AdSlot;
use App\Models\Product;
use App\Models\Store;
use App\Support\PeringkatService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeringkatIklanSeeder extends Seeder
{
    /**
     * Buat data uji iklan (AdSlot) aktif untuk produk real.
     * Idempotent: hanya produk yang belum punya iklan aktif yang dibuatkan slot.
     */
    public function run(): void
    {
        $nominalBids = [1200000, 750000, 500000, 350000, 250000, 100000, 400000, 200000];

        $products = Product::query()
            ->where('status', Product::STATUS_AKTIF)
            ->whereHas('store', fn ($q) => $q->where('status', Store::STATUS_AKTIF))
            ->whereDoesntHave('adSlot')
            ->orderBy('product_id')
            ->get();

        if ($products->isEmpty()) {
            $this->command->warn('Tidak ada produk aktif (dengan toko aktif) tanpa iklan. Seeder dilewati.');

            return;
        }

        $today = now()->toDateString();
        $created = 0;

        DB::transaction(function () use ($products, $nominalBids, $today, &$created) {
            foreach ($products->take(count($nominalBids)) as $index => $product) {
                $nominal = $nominalBids[$index % count($nominalBids)];

                AdSlot::create([
                    'product_id' => $product->product_id,
                    'store_id' => $product->store_id,
                    'nominal_bid' => $nominal,
                    'payment_status' => AdSlot::PAYMENT_TERVERIFIKASI,
                    'paid_at' => now(),
                    'tanggal_mulai' => $today,
                    'tanggal_selesai' => now()->addDays(PeringkatService::resolveHari($nominal))->toDateString(),
                    'status' => AdSlot::STATUS_AKTIF,
                ]);

                $created++;
            }
        });

        $this->command->info("Seeder Peringkat Iklan selesai: {$created} slot iklan aktif dibuat.");
    }
}