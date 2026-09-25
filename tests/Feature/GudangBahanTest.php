<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductionOrderBahan;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class GudangBahanTest extends TestCase
{
    use DatabaseTransactions;

    public function test_bahan_page_lists_products_needing_bahan(): void
    {
        [$gudang, $product] = $this->productWithGudang('Produk Butuh Bahan');

        $response = $this->actingAsFresh($gudang)->get(route('gudang.bahan-produk'));

        $response->assertOk();
        $response->assertSee('Produk Butuh Bahan', false);
        $response->assertSee('Belum ada', false);
        $response->assertSee('Input Bahan', false);
    }

    public function test_gudang_saves_bahan_for_product(): void
    {
        [$gudang, $product] = $this->productWithGudang();

        $this->actingAsFresh($gudang)->post(
            route('gudang.bahan-produk.store', ['product' => $product->product_id]),
            ['bahan' => [
                ['nama_bahan' => 'Kancing', 'jumlah' => 4, 'satuan' => 'pcs'],
                ['nama_bahan' => 'Kain Katun', 'jumlah' => '1.5', 'satuan' => 'meter'],
            ]]
        )->assertSessionHasNoErrors();

        $this->assertDatabaseHas('product_material_requirements', [
            'product_id' => $product->product_id,
            'nama_bahan' => 'Kancing',
            'satuan' => 'pcs',
        ]);

        $response = $this->actingAsFresh($gudang)->get(route('gudang.bahan-produk'));
        $response->assertOk();
        $response->assertSee('2 bahan', false);
    }

    public function test_gudang_bahan_replaces_existing_rows(): void
    {
        [$gudang, $product] = $this->productWithGudang();
        $url = route('gudang.bahan-produk.store', ['product' => $product->product_id]);

        $this->actingAsFresh($gudang)->post($url, ['bahan' => [
            ['nama_bahan' => 'Bahan Lama', 'jumlah' => 1, 'satuan' => 'pcs'],
        ]])->assertSessionHasNoErrors();

        $this->actingAsFresh($gudang)->post($url, ['bahan' => [
            ['nama_bahan' => 'Bahan Baru A', 'jumlah' => 2, 'satuan' => 'meter'],
            ['nama_bahan' => 'Bahan Baru B', 'jumlah' => 3, 'satuan' => 'pcs'],
        ]])->assertSessionHasNoErrors();

        $this->assertSame(2, $product->materialRequirements()->count());
        $this->assertDatabaseMissing('product_material_requirements', [
            'product_id' => $product->product_id,
            'nama_bahan' => 'Bahan Lama',
        ]);
    }

    public function test_gudang_bahan_validation(): void
    {
        [$gudang, $product] = $this->productWithGudang();

        $response = $this->actingAsFresh($gudang)->post(
            route('gudang.bahan-produk.store', ['product' => $product->product_id]),
            ['bahan' => [['nama_bahan' => '', 'jumlah' => 1, 'satuan' => 'pcs']]]
        );

        $response->assertStatus(302);
        $response->assertSessionHasErrors('bahan.0.nama_bahan');
        $this->assertSame(0, $product->materialRequirements()->count());
    }

    public function test_gudang_bahan_rejects_outside_scope(): void
    {
        [$gudang, $storeId, $product] = $this->productWithGudangStore('Produk Asing Bahan');
        $otherStoreId = \App\Models\Store::where('store_id', '!=', $storeId)->where('status', 'aktif')->value('store_id');
        if (! $otherStoreId) {
            $this->markTestSkipped('Tidak ada toko lain aktif untuk uji scope.');
        }
        $product->update(['store_id' => $otherStoreId]);

        $response = $this->actingAsFresh($gudang)->post(
            route('gudang.bahan-produk.store', ['product' => $product->product_id]),
            ['bahan' => [['nama_bahan' => 'Kancing', 'jumlah' => 1, 'satuan' => 'pcs']]]
        );

        $response->assertStatus(302);
        $this->assertSame(0, $product->materialRequirements()->count());
    }

    public function test_product_submit_notifies_store_gudang(): void
    {
        [$admin, $storeId] = $this->admin();
        $gudang = $this->gudangOf($storeId);
        $name = 'Produk Notif Gudang '.Str::random(6);

        $this->createProduct($admin, $name);

        $this->assertTrue(
            Notification::where('user_id', $gudang->user_id)
                ->where('judul', 'Produk Baru Perlu Bahan')
                ->where('pesan', 'like', '%'.$name.'%')
                ->exists(),
            'Gudang se-toko harus dinotifikasi produk baru.'
        );
    }

    public function test_accept_copies_recipe_to_order_bahan(): void
    {
        [$admin, $storeId] = $this->admin();
        [$produksi] = $this->userWithRole(Role::PRODUKSI, $storeId);
        [$gudang] = $this->userWithRole(Role::GUDANG, $storeId);
        $product = $this->createProduct($admin, 'Produk Salin Bahan');

        $this->actingAsFresh($gudang)->post(
            route('gudang.bahan-produk.store', ['product' => $product->product_id]),
            ['bahan' => [['nama_bahan' => 'Kancing', 'jumlah' => 4, 'satuan' => 'pcs']]]
        )->assertSessionHasNoErrors();

        $variant = $product->variants()->firstOrFail();
        $order = $this->orderDiproses($admin, $storeId, $variant, 2);

        $this->actingAsFresh($produksi)->post(
            route('produksi.data-produksi.accept', ['order' => $order->order_id])
        )->assertSessionHasNoErrors();

        // Disalin per item (2 baris × qty 1 → masing-masing 4 pcs).
        $this->assertSame(2, ProductionOrderBahan::where('order_id', $order->order_id)
            ->where('sumber', ProductionOrderBahan::SUMBER_ADMIN)->count());
        $this->assertSame(8.0, (float) ProductionOrderBahan::where('order_id', $order->order_id)
            ->where('sumber', ProductionOrderBahan::SUMBER_ADMIN)->sum('jumlah'));

        $this->assertDatabaseHas('production_order_bahan', [
            'order_id' => $order->order_id,
            'nama_bahan' => 'Kancing',
            'jumlah' => 4,
            'satuan' => 'pcs',
            'sumber' => ProductionOrderBahan::SUMBER_ADMIN,
        ]);

        $response = $this->actingAsFresh($produksi)->get(route('produksi.data-produksi'));
        $response->assertOk();
        $response->assertSee('Kancing', false);
    }

    public function test_produksi_can_add_extra_bahan_per_order(): void
    {
        [$admin, $storeId] = $this->admin();
        [$produksi] = $this->userWithRole(Role::PRODUKSI, $storeId);
        $product = $this->createProduct($admin, 'Produk Tambah Bahan');
        $variant = $product->variants()->firstOrFail();
        $order = $this->orderDiproses($admin, $storeId, $variant, 1);

        $this->actingAsFresh($produksi)->post(
            route('produksi.data-produksi.accept', ['order' => $order->order_id])
        )->assertSessionHasNoErrors();

        $this->actingAsFresh($produksi)->post(
            route('produksi.data-produksi.bahan', ['order' => $order->order_id]),
            ['bahan' => [['nama_bahan' => 'Benang', 'jumlah' => 1, 'satuan' => 'roll']]]
        )->assertSessionHasNoErrors();

        $this->assertDatabaseHas('production_order_bahan', [
            'order_id' => $order->order_id,
            'nama_bahan' => 'Benang',
            'sumber' => ProductionOrderBahan::SUMBER_PRODUKSI,
        ]);
    }

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }

    private function admin(): array
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
        $storeId = StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');

        $this->flushSession();

        return [$admin, $storeId];
    }

    private function userWithRole(string $role, int $storeId): array
    {
        $user = User::whereHas('role', fn ($q) => $q->where('nama_role', $role))
            ->where('status', User::STATUS_AKTIF)
            ->whereHas('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'))
            ->firstOrFail();

        $this->flushSession();

        return [$user];
    }

    private function gudangOf(int $storeId): User
    {
        return $this->userWithRole(Role::GUDANG, $storeId)[0];
    }

    /**
     * @return array{0: User, 1: Product}
     */
    private function productWithGudang(?string $name = null): array
    {
        [$admin] = $this->admin();
        $gudang = $this->gudangOf(StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id'));

        return [$gudang, $this->createProduct($admin, $name)];
    }

    /**
     * @return array{0: User, 1: int, 2: Product}
     */
    private function productWithGudangStore(?string $name = null): array
    {
        [$admin, $storeId] = $this->admin();
        $gudang = $this->gudangOf($storeId);

        return [$gudang, $storeId, $this->createProduct($admin, $name)];
    }

    private function createProduct(User $admin, ?string $name = null): Product
    {
        Storage::fake('public');
        $payload = [
            'nama_produk' => $name ?? 'Produk Uji Bahan Gudang',
            'harga_dasar' => '150000',
            'hpp' => '65000',
            'category_id' => Category::where('status', 'aktif')->value('category_id'),
            'tipe_produk' => 'regular',
            'deskripsi' => 'Deskripsi produk uji bahan gudang minimal sepuluh karakter.',
            'foto_produk' => [UploadedFile::fake()->image('produk.jpg', 600, 800)],
            'ukuran_terpilih' => 'M',
            'varian_stok' => [
                ['ukuran' => 'M', 'warna' => '', 'stok' => 5, 'stok_minimum' => 0],
            ],
        ];

        $this->actingAsFresh($admin)->post(route('admin.produk.store'), $payload)->assertSessionHasNoErrors();

        return Product::where('nama_produk', $payload['nama_produk'])->firstOrFail();
    }

    private function orderDiproses(User $admin, int $storeId, $variant, int $qty): Order
    {
        $email = 'bahan-'.Str::random(8).'@example.com';
        $items = [];
        for ($i = 0; $i < $qty; $i++) {
            $items[] = ['product_variant_id' => $variant->product_variant_id, 'quantity' => 1];
        }

        $this->actingAsFresh($admin)->post(route('admin.pesanan.store'), [
            'tipe_pesanan' => 'offline',
            'nama_penerima' => 'Budi Bahan',
            'nomor_telepon' => '081234567890',
            'email_pelanggan' => $email,
            'alamat' => 'Jl. Merdeka No.1, Jakarta',
            'metode_bayar' => 'tunai',
            'items' => $items,
        ])->assertSessionHasNoErrors();

        $order = Order::whereHas('checkout', fn ($q) => $q->where('email_pelanggan', $email))->firstOrFail();
        $order->update(['status' => Order::STATUS_DIPROSES]);

        return $order->fresh();
    }
}
