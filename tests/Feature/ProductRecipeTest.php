<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductRecipeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_master_requires_hpp(): void
    {
        $payload = $this->masterPayload();
        unset($payload['hpp']);

        $response = $this->postMaster($payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('hpp');
    }

    public function test_master_stores_hpp_without_bahan(): void
    {
        [$admin, $product] = $this->createMaster('Produk Uji HPP');

        $this->assertSame(65000.0, (float) $product->fresh()->modal_produksi);

        $response = $this->actingAs($admin)->get(route('admin.produk'));

        $response->assertOk();
        $response->assertSee('Produk Uji HPP', false);
        $response->assertSee('Belum ada bahan produksi.', false);
    }

    public function test_detail_shows_hpp_margin_with_bahan(): void
    {
        [$admin, $product] = $this->createMaster('Produk Uji Margin');
        \App\Models\ProductMaterialRequirement::create([
            'product_id' => $product->product_id,
            'material_id' => null,
            'nama_bahan' => 'Kancing',
            'satuan' => 'pcs',
            'jumlah_per_unit' => 4,
            'biaya_per_unit' => 0,
        ]);
        \App\Models\ProductMaterialRequirement::create([
            'product_id' => $product->product_id,
            'material_id' => null,
            'nama_bahan' => 'Kain Katun',
            'satuan' => 'meter',
            'jumlah_per_unit' => 2,
            'biaya_per_unit' => 0,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.produk'));

        $response->assertOk();
        $response->assertSee('2 bahan', false);
        $response->assertSee('HPP Rp 65.000', false);
        $response->assertSee('Margin Rp 85.000 (56,67%)', false);
        $response->assertSee('Kancing', false);
    }

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }

    /**
     * @return array{0: User, 1: Product}
     */
    private function createMaster(?string $name = null): array
    {
        Storage::fake('public');
        $admin = User::whereHas('role', fn ($query) => $query->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($query) => $query->where('status', 'aktif'))
            ->firstOrFail();

        $payload = $this->masterPayload($name);

        $this->actingAsFresh($admin)->post(route('admin.produk.store'), $payload)->assertSessionHasNoErrors();

        return [$admin, Product::where('nama_produk', $payload['nama_produk'])->firstOrFail()];
    }

    private function postMaster(array $payload)
    {
        Storage::fake('public');

        return $this->actingAs($this->adminUser())->post(route('admin.produk.store'), $payload);
    }

    private function adminUser(): User
    {
        return User::whereHas('role', fn ($query) => $query->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($query) => $query->where('status', 'aktif'))
            ->firstOrFail();
    }

    private function masterPayload(?string $name = null): array
    {
        return [
            'nama_produk' => $name ?? 'Produk Uji HPP Regression',
            'harga_dasar' => '150000',
            'hpp' => '65.000',
            'category_id' => Category::where('status', 'aktif')->value('category_id'),
            'tipe_produk' => 'regular',
            'deskripsi' => 'Deskripsi produk uji HPP minimal sepuluh karakter.',
            'foto_produk' => [UploadedFile::fake()->image('produk.jpg', 600, 800)],
            'ukuran_terpilih' => 'M',
            'warna' => ['Merah'],
            'warna_hex' => ['#c62828'],
            'varian_stok' => [
                ['ukuran' => 'M', 'warna' => 'Merah', 'stok' => 1, 'stok_minimum' => 0],
            ],
        ];
    }
}
