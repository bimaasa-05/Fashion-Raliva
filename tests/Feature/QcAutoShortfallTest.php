<?php

namespace Tests\Feature;

use App\Models\Checkout;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class QcAutoShortfallTest extends TestCase
{
    use DatabaseTransactions;

    public function test_qc_auto_takes_shortfall_from_warehouse(): void
    {
        // Pesan 6 (4+2), lulus 5 → kurang 1, stok cukup.
        [$produksi, $gudang, $order, $variants] = $this->orderSiapQc('auto-', 50, 50);
        $stokAwal = WarehouseStock::whereIn('product_variant_id', $variants)->sum('jumlah_stok');

        $this->actingAs($produksi)->post(
            route('produksi.pemeriksaan-kualitas.store', ['order' => $order->order_id]),
            ['jumlah_lulus' => 5]
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(Order::STATUS_SIAP_KIRIM, $order->status);
        $this->assertSame(6, (int) $order->jumlah_berhasil, 'Berhasil = lulus + dari gudang.');
        $this->assertSame(1, (int) $order->jumlah_gagal, 'Gagal tetap cacat produksi.');
        $this->assertSame(0, (int) $order->kekurangan_gudang);
        $this->assertSame($stokAwal - 1, (int) WarehouseStock::whereIn('product_variant_id', $variants)->sum('jumlah_stok'));
        $this->assertSame(1, (int) $order->items()->sum('qty_dari_gudang'));
        $this->assertTrue(
            StockMovement::where('sumber_id', $order->order_id)->where('alasan', 'like', 'Penutup kekurangan%')->exists()
        );
        $this->assertTrue(
            Notification::where('judul', 'Kekurangan Terpenuhi Otomatis dari Gudang')
                ->where('pesan', 'like', '%'.$order->nomor_order.'%')
                ->exists()
        );
    }

    public function test_qc_keeps_shortfall_when_warehouse_empty(): void
    {
        [$produksi, $gudang, $order] = $this->orderSiapQc('empty-', 0, 0);

        $this->actingAs($produksi)->post(
            route('produksi.pemeriksaan-kualitas.store', ['order' => $order->order_id]),
            ['jumlah_lulus' => 5]
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(5, (int) $order->jumlah_berhasil);
        $this->assertSame(1, (int) $order->kekurangan_gudang, 'Shortage tetap jadi tugas Gudang.');
        $this->assertTrue(
            Notification::where('judul', 'Kekurangan Produksi — Siapkan dari Gudang')
                ->where('pesan', 'like', '%'.$order->nomor_order.'%')
                ->exists()
        );
    }

    public function test_qc_takes_partial_when_stock_half(): void
    {
        // Kurang 2 tapi stok total hanya 1 → terambil 1, sisa 1.
        [$produksi, $gudang, $order, $variants] = $this->orderSiapQc('half-', 1, 0);

        $this->actingAs($produksi)->post(
            route('produksi.pemeriksaan-kualitas.store', ['order' => $order->order_id]),
            ['jumlah_lulus' => 4]
        )->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertSame(5, (int) $order->jumlah_berhasil);
        $this->assertSame(1, (int) $order->kekurangan_gudang);
        $this->assertSame(0, (int) WarehouseStock::whereIn('product_variant_id', $variants)->sum('jumlah_stok'));
    }

    public function test_siapkan_after_qc_covered_does_nothing(): void
    {
        [$produksi, $gudang, $order, $variants] = $this->orderSiapQc('idle-', 50, 50);
        $stokAwal = WarehouseStock::whereIn('product_variant_id', $variants)->sum('jumlah_stok');

        $this->actingAs($produksi)->post(
            route('produksi.pemeriksaan-kualitas.store', ['order' => $order->order_id]),
            ['jumlah_lulus' => 5]
        )->assertSessionHasNoErrors();

        $this->flushSession();
        $this->actingAs($gudang)->post(route('gudang.kekurangan.siapkan', $order->order_id))
            ->assertSessionHas('toast');

        $this->assertSame($stokAwal - 1, (int) WarehouseStock::whereIn('product_variant_id', $variants)->sum('jumlah_stok'),
            'Tidak boleh potong dua kali.');
    }

    public function test_utama_warehouse_is_prioritized(): void
    {
        // Gudang utama (dibuat pertama) stoknya lebih kecil dari cabang —
        // yang dipakai tetap gudang utama.
        [$produksi, $gudang, $order, $variants] = $this->orderSiapQc('prio-', 50, 50);
        $utamaId = WarehouseStock::whereIn('product_variant_id', $variants)->min('warehouse_id');
        $utamaNama = Warehouse::find($utamaId)->nama_gudang;
        $cabang = Warehouse::create([
            'store_id' => $order->store_id, 'nama_gudang' => 'Cabang Uji Prio',
            'alamat' => 'Jl. Cabang', 'status' => Warehouse::STATUS_AKTIF,
        ]);
        WarehouseStock::create([
            'warehouse_id' => $cabang->warehouse_id,
            'product_variant_id' => $variants[0],
            'jumlah_stok' => 200,
        ]);

        $this->actingAs($produksi)->post(
            route('produksi.pemeriksaan-kualitas.store', ['order' => $order->order_id]),
            ['jumlah_lulus' => 5]
        )->assertSessionHasNoErrors();

        $movement = StockMovement::where('sumber_id', $order->order_id)
            ->where('alasan', 'like', 'Penutup kekurangan%')
            ->firstOrFail();
        $this->assertSame($utamaId, (int) $movement->warehouse_id, 'Gudang utama dipakai duluan.');
        $this->assertStringContainsString($utamaNama, $movement->alasan);
        $this->assertSame($utamaNama, $order->fresh()->namaGudangShortfall());
    }

    /**
     * Order 6 pcs (4+2) status menunggu_qc.
     *
     * @return array{User,User,Order,array}
     */
    private function orderSiapQc(string $prefix, int $stokA, int $stokB): array
    {
        $owner = User::create([
            'role_id' => Role::where('nama_role', 'Owner')->firstOrFail()->role_id,
            'nama_lengkap' => 'Owner QC '.$prefix, 'email' => 'owner-qc-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'), 'status' => 'aktif',
        ]);
        $store = Store::create([
            'owner_id' => $owner->user_id, 'nama_toko' => 'Toko QC '.$prefix,
            'alamat' => 'Jl. QC No. 1', 'status' => Store::STATUS_AKTIF,
        ]);
        $gudangRole = Role::where('nama_role', 'Gudang')->firstOrFail()->role_id;
        $produksiRole = Role::where('nama_role', 'Produksi')->firstOrFail()->role_id;
        $produksi = User::create([
            'role_id' => $produksiRole,
            'nama_lengkap' => 'Produksi QC '.$prefix, 'email' => 'produksi-qc-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'), 'status' => 'aktif',
        ]);
        $gudang = User::create([
            'role_id' => $gudangRole,
            'nama_lengkap' => 'Gudang QC '.$prefix, 'email' => 'gudang-idle-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'), 'status' => 'aktif',
        ]);
        StoreStaff::create(['store_id' => $store->store_id, 'user_id' => $produksi->user_id, 'status' => 'aktif']);
        StoreStaff::create(['store_id' => $store->store_id, 'user_id' => $gudang->user_id, 'status' => 'aktif']);
        $warehouse = Warehouse::create([
            'store_id' => $store->store_id, 'nama_gudang' => 'WH QC '.$prefix,
            'alamat' => 'Jl. WH', 'status' => Warehouse::STATUS_AKTIF,
        ]);

        $product = Product::create([
            'store_id' => $store->store_id, 'nama_produk' => 'Produk QC '.$prefix,
            'harga_dasar' => 100000, 'status' => 'aktif',
        ]);
        $variants = [];
        $stoks = [$stokA, $stokB];
        foreach (['A', 'B'] as $i => $suf) {
            $v = ProductVariant::create([
                'product_id' => $product->product_id, 'sku' => 'QC-'.$suf.'-'.uniqid(),
                'ukuran' => 'M', 'harga' => 100000, 'status' => 'aktif',
            ]);
            $variants[] = $v->product_variant_id;
            WarehouseStock::create([
                'warehouse_id' => $warehouse->warehouse_id,
                'product_variant_id' => $v->product_variant_id,
                'jumlah_stok' => $stoks[$i],
            ]);
        }

        $cust = User::create([
            'role_id' => Role::where('nama_role', 'Customer')->firstOrFail()->role_id,
            'nama_lengkap' => 'Cust QC '.$prefix, 'email' => 'cust-qc-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'), 'status' => 'aktif',
        ]);
        $checkout = Checkout::create([
            'user_id' => $cust->user_id, 'subtotal' => 600000, 'grand_total' => 600000, 'status' => 'dibayar',
        ]);
        $order = Order::create([
            'checkout_id' => $checkout->checkout_id, 'store_id' => $store->store_id,
            'nomor_order' => 'QC-'.strtoupper(rtrim($prefix, '-')).'-'.uniqid(),
            'status' => Order::STATUS_MENUNGGU_QC,
            'subtotal' => 600000, 'grand_total' => 600000,
            'jumlah_berhasil' => 6, 'jumlah_gagal' => 0,
        ]);
        OrderItem::create([
            'order_id' => $order->order_id, 'product_variant_id' => $variants[0],
            'nama_produk_snapshot' => 'Varian A', 'harga_snapshot' => 100000,
            'quantity' => 4, 'subtotal' => 400000, 'total' => 400000,
        ]);
        OrderItem::create([
            'order_id' => $order->order_id, 'product_variant_id' => $variants[1],
            'nama_produk_snapshot' => 'Varian B', 'harga_snapshot' => 100000,
            'quantity' => 2, 'subtotal' => 200000, 'total' => 200000,
        ]);

        return [$produksi, $gudang, $order->fresh(), $variants];
    }
}


