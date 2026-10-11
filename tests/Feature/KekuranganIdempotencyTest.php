<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Regresi P0-6: siapkan kekurangan idempoten — submit kedua tidak
 * memotong stok dua kali.
 */
class KekuranganIdempotencyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_double_siapkan_deducts_once(): void
    {
        [$gudang, $order, $variants] = $this->orderDenganKekurangan(3);

        $stokAwal = WarehouseStock::whereIn('product_variant_id', $variants)->sum('jumlah_stok');

        $this->actingAs($gudang)->post(route('gudang.kekurangan.siapkan', $order->order_id))
            ->assertSessionHasNoErrors();
        $this->assertSame(0, (int) $order->fresh()->kekurangan_gudang);

        // Submit kedua: ditolak ramah, stok tidak terpotong lagi.
        $this->actingAs($gudang)->post(route('gudang.kekurangan.siapkan', $order->order_id))
            ->assertSessionHas('toast');

        $this->assertSame(0, (int) $order->fresh()->kekurangan_gudang);
        $this->assertSame(
            $stokAwal - 3,
            (int) WarehouseStock::whereIn('product_variant_id', $variants)->sum('jumlah_stok')
        );
    }

    /**
     * @return array{User, Order, array}
     */
    private function orderDenganKekurangan(int $kurang): array
    {
        $prefix = 'idem-';
        $owner = User::create([
            'role_id' => Role::where('nama_role', Role::OWNER)->value('role_id'),
            'nama_lengkap' => 'Owner Kek ' . $prefix,
            'email' => 'owner-kek-' . Str::random(8) . '@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Kek ' . $prefix,
            'alamat' => 'Jl. Kek No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);
        $gudang = User::create([
            'role_id' => Role::where('nama_role', Role::GUDANG)->value('role_id'),
            'nama_lengkap' => 'Gudang Kek ' . $prefix,
            'email' => 'gudang-kek-' . Str::random(8) . '@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        StoreStaff::create(['store_id' => $store->store_id, 'user_id' => $gudang->user_id, 'status' => 'aktif']);
        $warehouse = Warehouse::create([
            'store_id' => $store->store_id,
            'nama_gudang' => 'WH Kek ' . $prefix,
            'alamat' => 'Jl. WH',
            'status' => Warehouse::STATUS_AKTIF,
        ]);

        $product = \App\Models\Product::create([
            'store_id' => $store->store_id,
            'nama_produk' => 'Produk Kek ' . $prefix,
            'harga_dasar' => 100000,
            'status' => 'aktif',
        ]);
        $variants = [];
        foreach (['A', 'B'] as $suf) {
            $variants[] = \App\Models\ProductVariant::create([
                'product_id' => $product->product_id,
                'sku' => 'KEK-' . $suf . '-' . uniqid(),
                'ukuran' => 'M',
                'harga' => 100000,
                'status' => 'aktif',
            ])->product_variant_id;
        }
        foreach ($variants as $v) {
            WarehouseStock::create([
                'warehouse_id' => $warehouse->warehouse_id,
                'product_variant_id' => $v,
                'jumlah_stok' => 50,
            ]);
        }

        $cust = User::create([
            'role_id' => Role::where('nama_role', Role::CUSTOMER)->value('role_id'),
            'nama_lengkap' => 'Cust Kek ' . $prefix,
            'email' => 'cust-kek-' . Str::random(8) . '@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $checkout = \App\Models\Checkout::create([
            'user_id' => $cust->user_id,
            'subtotal' => 600000,
            'grand_total' => 600000,
            'status' => 'dibayar',
        ]);
        $order = Order::create([
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $store->store_id,
            'nomor_order' => 'KEK-' . strtoupper(Str::random(8)),
            'status' => Order::STATUS_SIAP_KIRIM,
            'subtotal' => 600000,
            'grand_total' => 600000,
            'jumlah_berhasil' => 6 - $kurang,
            'jumlah_gagal' => $kurang,
            'kekurangan_gudang' => $kurang,
        ]);
        \App\Models\OrderItem::create([
            'order_id' => $order->order_id, 'product_variant_id' => $variants[0],
            'nama_produk_snapshot' => 'Varian A', 'harga_snapshot' => 100000, 'quantity' => 4, 'subtotal' => 400000, 'total' => 400000,
        ]);
        \App\Models\OrderItem::create([
            'order_id' => $order->order_id, 'product_variant_id' => $variants[1],
            'nama_produk_snapshot' => 'Varian B', 'harga_snapshot' => 100000, 'quantity' => 2, 'subtotal' => 200000, 'total' => 200000,
        ]);

        return [$gudang, $order->fresh(), $variants];
    }
}
