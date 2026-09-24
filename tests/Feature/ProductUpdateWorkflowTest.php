<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductUpdateRequest;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductUpdateWorkflowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_update_creates_pending_request_without_changing_product(): void
    {
        Storage::fake('public');
        [$admin, $storeId] = $this->admin();
        $product = $this->updatableProduct($storeId);
        $oldName = $product->nama_produk;

        $response = $this->actingAs($admin)->put(route('admin.produk.update', $product), [
            'nama_produk' => $oldName.' Revisi',
            'harga_dasar' => (string) $product->harga_dasar,
            'category_id' => $product->category_id,
            'tipe_produk' => $product->tipe_produk,
            'deskripsi' => $product->deskripsi,
            'foto_produk' => [UploadedFile::fake()->image('revisi.jpg', 600, 800)],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertSame($oldName, $product->fresh()->nama_produk);
        $request = ProductUpdateRequest::where('product_id', $product->product_id)
            ->where('status', ProductUpdateRequest::STATUS_PENDING)
            ->firstOrFail();
        $staged = $request->staged_images[0] ?? '';
        $this->assertNotSame('', $staged);
        Storage::disk('public')->assertExists($staged);
    }

    public function test_second_update_is_blocked_while_request_is_pending(): void
    {
        Storage::fake('public');
        [$admin, $storeId] = $this->admin();
        $product = $this->updatableProduct($storeId);
        $payload = [
            'nama_produk' => $product->nama_produk.' Revisi',
            'harga_dasar' => (string) $product->harga_dasar,
            'category_id' => $product->category_id,
            'tipe_produk' => $product->tipe_produk,
            'deskripsi' => $product->deskripsi,
            'foto_produk' => [UploadedFile::fake()->image('revisi.jpg', 600, 800)],
        ];

        $this->actingAs($admin)->put(route('admin.produk.update', $product), $payload)->assertSessionHas('success');
        $response = $this->actingAs($admin)->put(route('admin.produk.update', $product), $payload);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertSame(1, ProductUpdateRequest::where('product_id', $product->product_id)
            ->where('status', ProductUpdateRequest::STATUS_PENDING)
            ->count());
    }

    public function test_admin_product_list_shows_pending_badge(): void
    {
        [$admin, $storeId] = $this->admin();
        $product = $this->updatableProduct($storeId);
        $product->variants()->firstOrFail()->update(['warna' => 'Tosca Elektrik', 'warna_hex' => '#123456']);
        ProductUpdateRequest::create([
            'product_id' => $product->product_id,
            'store_id' => $storeId,
            'requested_by' => $admin->user_id,
            'status' => ProductUpdateRequest::STATUS_PENDING,
            'before_snapshot' => [],
            'after_payload' => ['nama_produk' => $product->nama_produk],
        ]);

        $response = $this->actingAs($admin)->get(route('admin.produk'));

        $response->assertOk();
        $response->assertSee('Pengajuan Pending', false);
        $response->assertSee('&quot;hex&quot;:&quot;#123456&quot;', false);
    }

    public function test_superadmin_review_lists_pending_request(): void
    {
        [$admin, $storeId] = $this->admin();
        $product = $this->updatableProduct($storeId);
        ProductUpdateRequest::create([
            'product_id' => $product->product_id,
            'store_id' => $storeId,
            'requested_by' => $admin->user_id,
            'status' => ProductUpdateRequest::STATUS_PENDING,
            'before_snapshot' => ['product' => ['nama_produk' => $product->nama_produk]],
            'after_payload' => ['nama_produk' => $product->nama_produk.' Review'],
        ]);

        $response = $this->actingAs($this->superAdmin())->get(route('superadmin.perubahan-produk'));

        $response->assertOk();
        $response->assertSee($product->nama_produk, false);
        $response->assertSee('Setujui', false);
    }

    public function test_superadmin_approve_applies_request(): void
    {
        Storage::fake('public');
        [$admin, $storeId] = $this->admin();
        $superAdmin = $this->superAdmin();
        $product = $this->updatableProduct($storeId);
        $staged = 'pending-product-updates/uji/baru.jpg';
        Storage::disk('public')->put($staged, 'isi');
        $request = ProductUpdateRequest::create([
            'product_id' => $product->product_id,
            'store_id' => $storeId,
            'requested_by' => $admin->user_id,
            'status' => ProductUpdateRequest::STATUS_PENDING,
            'before_snapshot' => ['product' => ['nama_produk' => $product->nama_produk]],
            'after_payload' => [
                'nama_produk' => $product->nama_produk.' Disetujui',
                'harga_dasar' => $product->harga_dasar,
                'category_id' => $product->category_id,
                'tipe_produk' => $product->tipe_produk,
                'deskripsi' => $product->deskripsi,
                'staged_images' => [$staged],
            ],
            'remove_image_ids' => [],
            'staged_images' => [$staged],
        ]);

        $response = $this->actingAs($superAdmin)->post(route('superadmin.perubahan-produk.setujui', [$product, $request]));

        $response->assertStatus(302);
        $this->assertSame($product->nama_produk.' Disetujui', $product->fresh()->nama_produk);
        $this->assertDatabaseHas('product_images', ['product_id' => $product->product_id, 'file_gambar' => 'products/'.$product->product_id.'-'.$this->stagedUuid($request).'.jpg']);
        $this->assertSame(ProductUpdateRequest::STATUS_DISETUJUI, $request->fresh()->status);
    }

    public function test_superadmin_reject_removes_staged_files(): void
    {
        Storage::fake('public');
        [$admin, $storeId] = $this->admin();
        $superAdmin = $this->superAdmin();
        $product = $this->updatableProduct($storeId);
        $staged = 'pending-product-updates/uji/ditolak.jpg';
        Storage::disk('public')->put($staged, 'isi');
        $request = ProductUpdateRequest::create([
            'product_id' => $product->product_id,
            'store_id' => $storeId,
            'requested_by' => $admin->user_id,
            'status' => ProductUpdateRequest::STATUS_PENDING,
            'before_snapshot' => ['product' => ['nama_produk' => $product->nama_produk]],
            'after_payload' => [
                'nama_produk' => $product->nama_produk,
                'harga_dasar' => $product->harga_dasar,
                'category_id' => $product->category_id,
                'tipe_produk' => $product->tipe_produk,
                'deskripsi' => $product->deskripsi,
                'staged_images' => [$staged],
            ],
            'remove_image_ids' => [],
            'staged_images' => [$staged],
        ]);

        $response = $this->actingAs($superAdmin)->post(route('superadmin.perubahan-produk.tolak', [$product, $request]), [
            'alasan' => 'Foto usulan tidak sesuai standar katalog.',
        ]);

        $response->assertStatus(302);
        $this->assertSame($product->nama_produk, $product->fresh()->nama_produk);
        Storage::disk('public')->assertMissing($staged);
        $this->assertSame(ProductUpdateRequest::STATUS_DITOLAK, $request->fresh()->status);
    }

    private function admin(): array
    {
        $admin = User::whereHas('role', fn ($query) => $query->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($query) => $query->where('status', 'aktif'))
            ->firstOrFail();
        $storeId = StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');

        return [$admin, $storeId];
    }

    private function superAdmin(): User
    {
        return User::whereHas('role', fn ($query) => $query->where('nama_role', Role::SUPER_ADMIN))->firstOrFail();
    }

    private function updatableProduct(int $storeId): Product
    {
        $page = Product::where('store_id', $storeId)
            ->withCount('images')
            ->orderByDesc('created_at')
            ->orderByDesc('product_id')
            ->paginate(12);

        return $page->first(fn ($product) => $product->images_count < 5)
            ?? throw new \RuntimeException('Tidak ada produk dengan slot foto untuk pengujian.');
    }

    private function stagedUuid(ProductUpdateRequest $request): string
    {
        $image = \App\Models\ProductImage::where('product_id', $request->product_id)
            ->orderByDesc('product_image_id')
            ->firstOrFail();

        return substr(basename($image->file_gambar, '.jpg'), strlen($request->product_id.'-'));
    }
}
