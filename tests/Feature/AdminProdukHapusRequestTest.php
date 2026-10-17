<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductUpdateRequest;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminProdukHapusRequestTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Assertion memakai string Indonesia; kunci locale agar deterministik meski .env memakai en.
        app()->setLocale('id');
    }

    public function test_ajukan_hapus_membuat_pending_tanpa_menghapus_produk(): void
    {
        [$admin, $storeId] = $this->admin();
        $product = $this->deletableProduct($storeId);

        $this->actingAs($admin)->delete(
            route('admin.produk.destroy', $product),
            ['alasan' => 'Produk duplikat, stok tidak pernah masuk sepuluh karakter.']
        )->assertSessionHasNoErrors();

        $this->assertTrue(Product::whereKey($product->product_id)->exists());
        $req = ProductUpdateRequest::where('product_id', $product->product_id)
            ->where('status', ProductUpdateRequest::STATUS_PENDING)
            ->firstOrFail();
        $this->assertSame(ProductUpdateRequest::TIPE_HAPUS, $req->tipe);
        $this->assertSame('hapus', $req->after_payload['aksi'] ?? null);
    }

    public function test_ajukan_hapus_ditolak_bila_sudah_ada_pending(): void
    {
        [$admin, $storeId] = $this->admin();
        $product = $this->deletableProduct($storeId);
        ProductUpdateRequest::create([
            'product_id' => $product->product_id,
            'store_id' => $storeId,
            'requested_by' => $admin->user_id,
            'status' => ProductUpdateRequest::STATUS_PENDING,
            'tipe' => ProductUpdateRequest::TIPE_UBAH,
            'before_snapshot' => [],
            'after_payload' => ['nama_produk' => $product->nama_produk],
        ]);

        $this->actingAs($admin)->delete(
            route('admin.produk.destroy', $product),
            ['alasan' => 'Alasan penghapusan yang cukup panjang.']
        )->assertSessionHas('error');

        $this->assertSame(1, ProductUpdateRequest::where('product_id', $product->product_id)
            ->where('status', ProductUpdateRequest::STATUS_PENDING)->count());
    }

    public function test_ajukan_hapus_ditolak_bila_produk_berriwayat(): void
    {
        [$admin, $storeId] = $this->admin();
        $product = Product::where('store_id', $storeId)
            ->whereHas('variants.orderItems')
            ->firstOrFail();

        $this->actingAs($admin)->delete(
            route('admin.produk.destroy', $product),
            ['alasan' => 'Alasan penghapusan yang cukup panjang.']
        )->assertSessionHas('error');

        $this->assertTrue(Product::whereKey($product->product_id)->exists());
        $this->assertFalse(ProductUpdateRequest::where('product_id', $product->product_id)
            ->where('status', ProductUpdateRequest::STATUS_PENDING)->exists());
    }

    public function test_superadmin_setujui_hapus_menghapus_produk(): void
    {
        [$admin, $storeId] = $this->admin();
        $superAdmin = $this->superAdmin();
        $product = $this->deletableProduct($storeId);
        $productId = $product->product_id;

        $this->actingAs($admin)->delete(
            route('admin.produk.destroy', $product),
            ['alasan' => 'Produk duplikat, stok tidak pernah masuk sepuluh karakter.']
        )->assertSessionHasNoErrors();
        $req = ProductUpdateRequest::where('product_id', $productId)
            ->where('status', ProductUpdateRequest::STATUS_PENDING)
            ->firstOrFail();

        $this->actingAsFresh($superAdmin)->post(
            route('superadmin.perubahan-produk.setujui', [$productId, $req->product_update_request_id])
        )->assertSessionHasNoErrors();

        $this->assertFalse(Product::whereKey($productId)->exists());
        // Baris pengajuan dipertahankan sebagai arsip (product_id di-null-kan).
        $this->assertSame(
            ProductUpdateRequest::STATUS_DISETUJUI,
            $req->fresh()->status
        );
        $this->assertNull($req->fresh()->product_id);
    }

    public function test_superadmin_tolak_hapus_produk_tetap_ada(): void
    {
        [$admin, $storeId] = $this->admin();
        $superAdmin = $this->superAdmin();
        $product = $this->deletableProduct($storeId);

        $this->actingAs($admin)->delete(
            route('admin.produk.destroy', $product),
            ['alasan' => 'Produk duplikat, stok tidak pernah masuk sepuluh karakter.']
        )->assertSessionHasNoErrors();
        $req = ProductUpdateRequest::where('product_id', $product->product_id)
            ->where('status', ProductUpdateRequest::STATUS_PENDING)
            ->firstOrFail();

        $this->actingAsFresh($superAdmin)->post(
            route('superadmin.perubahan-produk.tolak', [$product->product_id, $req->product_update_request_id]),
            ['alasan' => 'Produk masih dibutuhkan katalog sepuluh.']
        )->assertSessionHasNoErrors();

        $this->assertTrue(Product::whereKey($product->product_id)->exists());
        $this->assertSame(ProductUpdateRequest::STATUS_DITOLAK, $req->fresh()->status);
    }

    public function test_halaman_produk_memuat_tombol_dan_modal_hapus(): void
    {
        [$admin] = $this->admin();

        $this->actingAsFresh($admin)->get(route('admin.produk'))
            ->assertOk()
            ->assertSee('data-produk-hapus', false)
            ->assertSee('id="modal-ajukan-hapus"', false)
            ->assertSee('id="form-ajukan-hapus"', false);
    }

    /**
     * Produk toko tanpa riwayat pesanan/ulasan/iklan.
     */
    private function deletableProduct(int $storeId): Product
    {
        $reviewed = \App\Models\Review::pluck('product_id')->all();
        $withAds = \App\Models\AdSlot::pluck('product_id')->all();

        return Product::where('store_id', $storeId)
            ->whereDoesntHave('variants.orderItems')
            ->whereNotIn('product_id', array_merge($reviewed, $withAds))
            ->orderByDesc('created_at')
            ->orderByDesc('product_id')
            ->firstOrFail();
    }

    /**
     * @return array{User, int}
     */
    private function admin(): array
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
            ->orderBy('user_id')
            ->firstOrFail();
        $storeId = (int) StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');

        $this->flushSession();

        return [$admin, $storeId];
    }

    private function superAdmin(): User
    {
        $superAdmin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->orderBy('user_id')
            ->firstOrFail();

        $this->flushSession();

        return $superAdmin;
    }

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }
}
