<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\SlotGrant;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductRecipeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_product_requires_recipe(): void
    {
        $payload = $this->payload();
        unset($payload['resep']);

        $response = $this->postProduct($payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('resep');
    }

    public function test_product_rejects_invalid_target(): void
    {
        $response = $this->postProduct(array_merge($this->payload(), ['target_produksi' => 0]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('target_produksi');
    }

    public function test_product_rejects_invalid_unit(): void
    {
        $payload = $this->payload();
        $payload['resep'][0]['satuan'] = 'ons';

        $response = $this->postProduct($payload);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('resep.0.satuan');
    }

    public function test_admin_detail_shows_recipe_summary(): void
    {
        Storage::fake('public');
        $admin = User::whereHas('role', fn ($query) => $query->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($query) => $query->where('status', 'aktif'))
            ->firstOrFail();
        $storeId = StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');
        SlotGrant::create([
            'store_id' => $storeId,
            'jumlah_slot' => 5,
            'tipe' => SlotGrant::TIPE_MANUAL,
            'keterangan' => 'Slot uji ringkasan resep.',
            'created_by' => $admin->user_id,
        ]);
        $payload = $this->payload();
        $payload['nama_produk'] = 'Produk Uji Ringkasan Resep';
        $payload['category_id'] = Category::where('status', 'aktif')->value('category_id');

        $this->actingAs($admin)->post(route('admin.produk.store'), $payload)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('products', ['nama_produk' => 'Produk Uji Ringkasan Resep', 'modal_produksi' => 15000]);

        $response = $this->actingAs($admin)->get(route('admin.produk'));

        $response->assertOk();
        $response->assertSee('Produk Uji Ringkasan Resep', false);
        $response->assertSee('Kain Katun', false);
        $response->assertSee('Target 10 unit', false);
        $response->assertSee('Modal Rp 15.000/unit', false);
        $response->assertDontSee('10.000,00', false);
        $response->assertSee('data-resep-rows', false);
        $response->assertSee('Kain Katun', false);
    }

    private function payload(): array
    {
        return [
            'nama_produk' => 'Produk Uji Resep Regression',
            'harga_dasar' => '150000',
            'tipe_produk' => 'regular',
            'deskripsi' => 'Deskripsi produk uji resep minimal sepuluh karakter.',
            'foto_produk' => [UploadedFile::fake()->image('produk.jpg', 600, 800)],
            'ukuran_terpilih' => 'M',
            'warna' => ['Merah'],
            'warna_hex' => ['#c62828'],
            'varian_stok' => [
                ['ukuran' => 'M', 'warna' => 'Merah', 'stok' => 1, 'stok_minimum' => 0],
            ],
            'target_produksi' => 10,
            'biaya_tambahan' => '0',
            'resep' => [
                ['material_id' => null, 'nama_bahan' => 'Kain Katun', 'satuan' => 'meter', 'jumlah_per_unit' => 2, 'biaya_per_unit' => '5000'],
            ],
            'operasional' => [
                ['nama_biaya' => 'Ongkos jahit', 'nominal' => '3.000'],
                ['nama_biaya' => 'Kemasan', 'nominal' => '2000'],
            ],
        ];
    }

    public function test_product_saves_operational_rows_and_total(): void
    {
        Storage::fake('public');
        $admin = User::whereHas('role', fn ($query) => $query->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($query) => $query->where('status', 'aktif'))
            ->firstOrFail();
        $storeId = StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');
        SlotGrant::create([
            'store_id' => $storeId,
            'jumlah_slot' => 5,
            'tipe' => SlotGrant::TIPE_MANUAL,
            'keterangan' => 'Slot uji biaya operasional.',
            'created_by' => $admin->user_id,
        ]);
        $payload = $this->payload();
        $payload['nama_produk'] = 'Produk Uji Operasional';
        $payload['category_id'] = Category::where('status', 'aktif')->value('category_id');

        $this->actingAs($admin)->post(route('admin.produk.store'), $payload)->assertSessionHasNoErrors();
        $product = Product::where('nama_produk', 'Produk Uji Operasional')->firstOrFail();
        $this->assertSame(15000.0, $product->modal_produksi);
        $this->assertSame(5000.0, $product->biaya_tambahan);
        $this->assertDatabaseHas('product_operational_costs', [
            'product_id' => $product->product_id,
            'nama_biaya' => 'Ongkos jahit',
            'nominal' => 3000,
        ]);
        $this->assertDatabaseHas('product_operational_costs', [
            'product_id' => $product->product_id,
            'nama_biaya' => 'Kemasan',
            'nominal' => 2000,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.produk'));
        $response->assertOk();
        $response->assertSee('Ongkos jahit', false);
    }

    private function postProduct(array $payload)
    {
        Storage::fake('public');
        $admin = User::whereHas('role', fn ($query) => $query->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($query) => $query->where('status', 'aktif'))
            ->firstOrFail();
        StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->firstOrFail();
        $payload['category_id'] = Category::where('status', 'aktif')->value('category_id');

        return $this->actingAs($admin)->post(route('admin.produk.store'), $payload);
    }
}
