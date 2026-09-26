<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Order;
use App\Models\ProductMaterialRequirement;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class QcTabRincianGudangTest extends TestCase
{
    use DatabaseTransactions;

    public function test_qc_page_defaults_to_menunggu_tab(): void
    {
        [$produksi, $storeId] = $this->userWithRole(Role::PRODUKSI);
        $menunggu = $this->orderStatus($storeId, Order::STATUS_MENUNGGU_QC);
        $siap = $this->orderStatus($storeId, Order::STATUS_SIAP_KIRIM);

        $response = $this->actingAsFresh($produksi)->get(route('produksi.pemeriksaan-kualitas'));

        $response->assertOk();
        $response->assertSee('Siap Untuk Dikirim', false);
        $response->assertSee($menunggu->nomor_order, false);
        $response->assertDontSee($siap->nomor_order, false);
    }

    public function test_qc_tab_siap_shows_ready_orders_read_only(): void
    {
        [$produksi, $storeId] = $this->userWithRole(Role::PRODUKSI);
        $menunggu = $this->orderStatus($storeId, Order::STATUS_MENUNGGU_QC);
        $siap = $this->orderStatus($storeId, Order::STATUS_SIAP_KIRIM, ['kekurangan_gudang' => 1]);

        $response = $this->actingAsFresh($produksi)->get(route('produksi.pemeriksaan-kualitas', ['tab' => 'siap']));

        $response->assertOk();
        $response->assertSee($siap->nomor_order, false);
        $response->assertSee('+1 dari Gudang', false);
        $response->assertDontSee($menunggu->nomor_order, false);
        $response->assertDontSee('modal-qc-' . $siap->order_id, false);
        $response->assertDontSee('Gagal — Hubungi Admin', false);
    }

    public function test_gudang_stok_modal_shows_recipe_and_history(): void
    {
        [$gudang, , $warehouse] = $this->gudang();
        $variant = $this->variantInWarehouse($warehouse->warehouse_id);

        ProductMaterialRequirement::create([
            'product_id' => $variant->product_id,
            'nama_bahan' => 'Kain Katun Premium Tes',
            'satuan' => 'meter',
            'jumlah_per_unit' => 1.5,
            'biaya_per_unit' => 25000,
        ]);
        StockMovement::create([
            'warehouse_id' => $warehouse->warehouse_id,
            'product_variant_id' => $variant->product_variant_id,
            'tipe_pergerakan' => StockMovement::TIPE_MASUK,
            'jumlah' => 3,
            'sumber_tipe' => 'manual',
            'alasan' => 'Tes riwayat gudang',
            'dibuat_oleh' => $gudang->user_id,
        ]);

        // Kunci gudang aktif agar request memakai warehouse yang sama dengan data uji.
        $response = $this->actingAsFresh($gudang)
            ->withSession(['gudang_active_warehouse_id' => $warehouse->warehouse_id])
            ->get(route('gudang.stok'));

        $response->assertOk();
        $response->assertSee('Rincian Bahan', false);
        $response->assertSee('Kain Katun Premium Tes', false);
        $response->assertSee('Riwayat Stok (10 Terakhir)', false);
        $response->assertSee('Tes riwayat gudang', false);
    }

    public function test_gudang_kekurangan_list_and_siapkan_clears_and_notifies(): void
    {
        [$gudang, $storeId] = $this->userWithRole(Role::GUDANG);
        $order = $this->orderStatus($storeId, Order::STATUS_SIAP_KIRIM, ['kekurangan_gudang' => 2]);

        $response = $this->actingAsFresh($gudang)->get(route('gudang.kekurangan'));
        $response->assertOk();
        $response->assertSee($order->nomor_order, false);
        $response->assertSee('2 pcs', false);

        $this->actingAsFresh($gudang)->post(
            route('gudang.kekurangan.siapkan', ['order' => $order->order_id])
        )->assertSessionHasNoErrors();

        $this->assertSame(0, (int) $order->fresh()->kekurangan_gudang);

        $produksi = $this->userWithRole(Role::PRODUKSI, $storeId)[0];
        $this->assertTrue(
            Notification::where('user_id', $produksi->user_id)
                ->where('judul', 'Kekurangan Disiapkan dari Gudang')
                ->where('pesan', 'like', '%' . $order->nomor_order . '%')
                ->exists(),
            'Produksi se-toko harus dinotifikasi kekurangan sudah disiapkan.'
        );

        $after = $this->actingAsFresh($gudang)->get(route('gudang.kekurangan'));
        $after->assertOk();
        $after->assertDontSee($order->nomor_order, false);
        $after->assertSee('Tidak ada kekurangan produksi', false);
    }

    public function test_gudang_kekurangan_rejects_foreign_store_and_empty_order(): void
    {
        [$gudang, $storeId] = $this->userWithRole(Role::GUDANG);
        $orderLuar = $this->foreignOrderWithKekurangan($storeId);
        $orderKosong = $this->orderStatus($storeId, Order::STATUS_SIAP_KIRIM, ['kekurangan_gudang' => 0]);

        $this->actingAsFresh($gudang)->post(
            route('gudang.kekurangan.siapkan', ['order' => $orderLuar->order_id])
        )->assertStatus(302);
        $this->assertSame(1, (int) $orderLuar->fresh()->kekurangan_gudang, 'Order toko lain tidak boleh disentuh.');

        $this->actingAsFresh($gudang)->post(
            route('gudang.kekurangan.siapkan', ['order' => $orderKosong->order_id])
        )->assertStatus(302);
        $this->assertSame(0, (int) $orderKosong->fresh()->kekurangan_gudang);

        $response = $this->actingAsFresh($gudang)->get(route('gudang.kekurangan'));
        $response->assertOk();
        $response->assertDontSee($orderLuar->nomor_order, false);
        $response->assertDontSee($orderKosong->nomor_order, false);
    }

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }

    private function userWithRole(string $role, ?int $storeId = null): array
    {
        $query = User::whereHas('role', fn ($q) => $q->where('nama_role', $role))
            ->where('status', User::STATUS_AKTIF)
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'));
        if ($storeId) {
            $query->whereHas('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'));
        }
        $user = $query->firstOrFail();
        $sid = StoreStaff::where('user_id', $user->user_id)->where('status', 'aktif')->value('store_id');

        $this->flushSession();

        if ($role === Role::GUDANG) {
            // Cerminkan ResolvesActiveWarehouse: status aktif + urut nama (fallback gudang pertama).
            $warehouse = $user->assignedWarehouses()
                ->wherePivot('status', 'aktif')
                ->where('warehouses.status', \App\Models\Warehouse::STATUS_AKTIF)
                ->orderBy('nama_gudang')
                ->firstOrFail();

            return [$user, $sid, $warehouse];
        }

        return [$user, $sid];
    }

    private function gudang(): array
    {
        [$gudang, $storeId, $warehouse] = $this->userWithRole(Role::GUDANG);

        return [$gudang, $storeId, $warehouse];
    }

    private function variantInWarehouse(int $warehouseId): ProductVariant
    {
        return ProductVariant::whereHas('warehouseStocks', fn ($q) => $q->where('warehouse_id', $warehouseId))
            ->firstOrFail();
    }

    private function adminOf(int $storeId): User
    {
        return User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->whereHas('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'))
            ->firstOrFail();
    }

    private function variant(int $storeId): ProductVariant
    {
        return ProductVariant::whereHas('product', fn ($q) => $q->where('store_id', $storeId))->firstOrFail();
    }

    private function orderStatus(int $storeId, string $status, array $extra = []): Order
    {
        $admin = $this->adminOf($storeId);
        $email = 'qc-tab-' . Str::random(8) . '@example.com';

        $this->actingAsFresh($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'Budi Tab',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Merdeka No.1, Jakarta',
            'metode_bayar' => 'tunai',
            'items' => [
                ['product_variant_id' => $this->variant($storeId)->product_variant_id, 'quantity' => 2],
            ],
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
        $order->update(array_merge([
            'status' => $status,
            'tanggal_qc' => now(),
            'jumlah_berhasil' => 2,
            'jumlah_gagal' => 0,
        ], $extra));

        return $order->fresh();
    }

    private function foreignOrderWithKekurangan(int $storeId): Order
    {
        $otherStoreId = \App\Models\Store::where('store_id', '!=', $storeId)
            ->where('status', 'aktif')
            ->value('store_id');
        if (! $otherStoreId) {
            $this->markTestSkipped('Tidak ada toko lain aktif untuk uji scope.');
        }

        $order = $this->orderStatus($storeId, Order::STATUS_SIAP_KIRIM, ['kekurangan_gudang' => 1]);
        $order->update(['store_id' => $otherStoreId]);

        return $order->fresh();
    }
}
