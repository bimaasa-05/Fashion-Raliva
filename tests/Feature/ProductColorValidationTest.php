<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\SlotGrant;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductColorValidationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unknown_custom_color_requires_hex(): void
    {
        $response = $this->postProduct(['warna' => ['Tosca Elektrik']]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('warna.0');
    }

    public function test_placeholder_color_name_is_rejected(): void
    {
        $response = $this->postProduct([
            'warna' => ['Warna 1'],
            'warna_hex' => ['#123456'],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('warna.0');
    }

    public function test_blank_color_name_is_rejected_when_mixed_with_colors(): void
    {
        $response = $this->postProduct([
            'warna' => ['Merah', ''],
            'warna_hex' => ['#c62828', '#123456'],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('warna.1');
    }

    public function test_product_without_color_can_be_created(): void
    {
        Storage::fake('public');
        $admin = User::whereHas('role', fn ($query) => $query->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($query) => $query->where('status', 'aktif'))
            ->firstOrFail();
        $storeId = StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');
        $category = Category::where('status', 'aktif')->firstOrFail();
        SlotGrant::create([
            'store_id' => $storeId,
            'jumlah_slot' => 5,
            'tipe' => SlotGrant::TIPE_MANUAL,
            'keterangan' => 'Slot uji produk tanpa warna.',
            'created_by' => $admin->user_id,
        ]);
        $name = 'Produk Tanpa Warna '.Str::random(8);

        $response = $this->actingAs($admin)->post(route('admin.produk.store'), [
            'nama_produk' => $name,
            'harga_dasar' => '150000',
            'category_id' => $category->category_id,
            'tipe_produk' => 'regular',
            'deskripsi' => 'Deskripsi produk uji tanpa warna minimal sepuluh karakter.',
            'foto_produk' => [UploadedFile::fake()->image('produk.jpg', 600, 800)],
            'ukuran_terpilih' => 'M',
            'varian_stok' => [
                ['ukuran' => 'M', 'warna' => '', 'stok' => 1, 'stok_minimum' => 0],
            ],
            'target_produksi' => 10,
            'biaya_tambahan' => '0',
            'resep' => [
                ['material_id' => null, 'nama_bahan' => 'Kain Katun', 'satuan' => 'meter', 'jumlah_per_unit' => 2, 'biaya_per_unit' => '5000'],
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $product = Product::where('nama_produk', $name)->firstOrFail();
        $this->assertTrue(ProductVariant::where('product_id', $product->product_id)->whereNull('warna')->exists());
        $this->assertSame(10, $product->target_produksi);
        $this->assertSame(10000.0, $product->modal_produksi);
        $this->assertDatabaseHas('product_material_requirements', [
            'product_id' => $product->product_id,
            'nama_bahan' => 'Kain Katun',
            'satuan' => 'meter',
        ]);
    }

    private function postProduct(array $colors)
    {
        Storage::fake('public');
        $admin = User::whereHas('role', fn ($query) => $query->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($query) => $query->where('status', 'aktif'))
            ->firstOrFail();
        StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->firstOrFail();
        $category = Category::where('status', 'aktif')->firstOrFail();

        return $this->actingAs($admin)->post(route('admin.produk.store'), array_merge([
            'nama_produk' => 'Produk Uji Warna Regression',
            'harga_dasar' => '150000',
            'category_id' => $category->category_id,
            'tipe_produk' => 'regular',
            'deskripsi' => 'Deskripsi produk uji warna minimal sepuluh karakter.',
            'foto_produk' => [UploadedFile::fake()->image('produk.jpg', 600, 800)],
            'ukuran_terpilih' => 'M',
            'varian_stok' => [
                ['ukuran' => 'M', 'warna' => 'Tosca Elektrik', 'stok' => 1, 'stok_minimum' => 0],
            ],
            'target_produksi' => 10,
            'biaya_tambahan' => '0',
            'resep' => [
                ['material_id' => null, 'nama_bahan' => 'Kain Katun', 'satuan' => 'meter', 'jumlah_per_unit' => 2, 'biaya_per_unit' => '5000'],
            ],
        ], $colors));
    }
}
