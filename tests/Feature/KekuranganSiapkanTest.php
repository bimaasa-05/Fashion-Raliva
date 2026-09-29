<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Support\StockDeductionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class KekuranganSiapkanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_siapkan_deducts_stock_proportionally(): void
    {
        [$gudang, $storeId, $order, $variants] = $this->orderDenganKekurangan('prop-', 3);

        $stokAwal = WarehouseStock::whereIn('product_variant_id', $variants)->sum('jumlah_stok');

        $this->actingAs($gudang)->post(route('gudang.kekurangan.siapkan', $order->order_id))
            ->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(0, (int) $order->kekurangan_gudang);
        // 3 berhasil produksi + 3 dari gudang = full 6 pcs.
        $this->assertSame(6, (int) $order->jumlah_berhasil);
        $this->assertSame(
            $stokAwal - 3,
            (int) WarehouseStock::whereIn('product_variant_id', $variants)->sum('jumlah_stok')
        );
        // Proporsional 3 pcs atas qty 4+2: varian A dapat 2, varian B dapat 1.
        $items = $order->items()->orderByDesc('quantity')->get();
        $this->assertSame(2, (int) $items[0]->qty_dari_gudang);
        $this->assertSame(1, (int) $items[1]->qty_dari_gudang);
        $this->assertTrue(
            StockMovement::where('sumber_id', $order->order_id)
                ->where('alasan', 'like', 'Penutup kekurangan%')
                ->exists()
        );
    }

    public function test_siapkan_rejected_when_stock_insufficient(): void
    {
        [$gudang, $storeId, $order, $variants] = $this->orderDenganKekurangan('nok-', 2);
        WarehouseStock::whereIn('product_variant_id', $variants)->update(['jumlah_stok' => 0]);

        $this->actingAs($gudang)->post(route('gudang.kekurangan.siapkan', $order->order_id))
            ->assertSessionHas('toast');

        $order->refresh();
        $this->assertSame(2, (int) $order->kekurangan_gudang);
        $this->assertFalse(
            StockMovement::where('sumber_id', $order->order_id)
                ->where('alasan', 'like', 'Penutup kekurangan%')
                ->exists()
        );
    }

    public function test_final_deduction_skips_warehouse_covered_qty(): void
    {
        [$gudang, $storeId, $order] = $this->orderDenganKekurangan('dbl-', 3);

        $this->actingAs($gudang)->post(route('gudang.kekurangan.siapkan', $order->order_id))
            ->assertSessionHasNoErrors();

        $hasil = StockDeductionService::deductForOrder($order->fresh());

        // Total 6 pcs, 3 sudah diambil gudang → final hanya potong 3.
        $this->assertSame(3, $hasil['deducted']);
    }

    /**
     * @return array{User,int,Order,array}
     */
    private function orderDenganKekurangan(string $prefix, int $kurang): array
    {
        $ownerRole = Role::where('nama_role', 'Owner')->firstOrFail();
        $owner = User::create([
            'role_id' => $ownerRole->role_id,
            'nama_lengkap' => 'Owner Kek '.$prefix,
            'email' => 'owner-kek-'.strtolower(rtrim($prefix, '-')).'-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Kek '.$prefix,
            'alamat' => 'Jl. Kek No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);
        $gudangRole = Role::where('nama_role', 'Gudang')->firstOrFail();
        $gudang = User::create([
            'role_id' => $gudangRole->role_id,
            'nama_lengkap' => 'Gudang Kek '.$prefix,
            'email' => 'gudang-kek-'.strtolower(rtrim($prefix, '-')).'-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        StoreStaff::create(['store_id' => $store->store_id, 'user_id' => $gudang->user_id, 'status' => 'aktif']);
        $warehouse = Warehouse::create([
            'store_id' => $store->store_id,
            'nama_gudang' => 'WH Kek '.$prefix,
            'alamat' => 'Jl. WH',
            'status' => Warehouse::STATUS_AKTIF,
        ]);

        $product = \App\Models\Product::create([
            'store_id' => $store->store_id,
            'nama_produk' => 'Produk Kek '.$prefix,
            'harga_dasar' => 100000,
            'status' => 'aktif',
        ]);
        $variants = [];
        foreach (['A', 'B'] as $suf) {
            $variants[] = \App\Models\ProductVariant::create([
                'product_id' => $product->product_id,
                'sku' => 'KEK-'.$suf.'-'.uniqid(),
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

        $custRole = Role::where('nama_role', 'Customer')->firstOrFail();
        $cust = User::create([
            'role_id' => $custRole->role_id,
            'nama_lengkap' => 'Cust Kek '.$prefix,
            'email' => 'cust-kek-'.strtolower(rtrim($prefix, '-')).'-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $checkout = \App\Models\Checkout::create([
            'user_id' => $cust->user_id,
            'subtotal' => 600000,
            'grand_total' => 600000,
            'status' => 'dibayar',
        ]);
        // Total 6 pcs (4 + 2): berhasil 6-kurang, gagal/kurang = $kurang.
        $order = Order::create([
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $store->store_id,
            'nomor_order' => 'KEK-'.strtoupper(rtrim($prefix, '-')).'-'.uniqid(),
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

        return [$gudang, $store->store_id, $order->fresh(), $variants];
    }
}

