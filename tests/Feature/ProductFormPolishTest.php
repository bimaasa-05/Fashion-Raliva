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

class ProductFormPolishTest extends TestCase
{
    use DatabaseTransactions;

    public function test_formatted_numbers_are_normalized_on_create(): void
    {
        Storage::fake('public');
        [$admin, $storeId, $categoryId] = $this->fixture();
        $name = 'Produk Format Ribuan '.Str::random(8);

        $response = $this->actingAs($admin)->post(route('admin.produk.store'), [
            'nama_produk' => $name,
            'harga_dasar' => '150.000',
            'hpp' => '65.000',
            'category_id' => $categoryId,
            'tipe_produk' => 'regular',
            'deskripsi' => 'Deskripsi produk uji format ribuan minimal sepuluh karakter.',
            'foto_produk' => [UploadedFile::fake()->image('produk.jpg', 600, 800)],
            'ukuran_terpilih' => 'M',
            'varian_stok' => [
                ['ukuran' => 'M', 'warna' => '', 'stok' => '1.000', 'stok_minimum' => '10.000'],
            ],
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $product = Product::where('nama_produk', $name)->firstOrFail();
        $this->assertSame(150000.0, (float) $product->harga_dasar);
        $variant = ProductVariant::where('product_id', $product->product_id)->firstOrFail();
        $this->assertSame(1000, (int) $variant->warehouseStocks->sum('jumlah_stok'));
    }

    public function test_add_form_uses_expected_order_and_grouped_inputs(): void
    {
        [$admin] = $this->fixture();

        $response = $this->actingAs($admin)->get(route('admin.produk'));
        $html = $response->getContent();

        $response->assertOk();
        $html = substr($html, strpos($html, 'id="modal-form-produk"'));
        $this->assertTrue(strpos($html, 'Foto Produk') < strpos($html, 'Informasi Dasar'));
        $this->assertTrue(strpos($html, 'Informasi Dasar') < strpos($html, 'Variasi &amp; Stok'));
        $this->assertTrue(strpos($html, 'id="fp-hpp"') < strpos($html, 'id="fp-harga"'));
        $this->assertFalse(strpos($html, 'id="modal-rencana-produk"'));
        $this->assertTrue(strpos($html, 'id="fp-nama"') < strpos($html, 'id="fp-tipe"'));
        $this->assertTrue(strpos($html, 'id="fp-tipe"') < strpos($html, 'id="fp-harga"'));
        $this->assertTrue(strpos($html, 'id="fp-harga"') < strpos($html, 'id="fp-deskripsi"'));
        $this->assertStringContainsString('data-ribuan-int', $html);
        $this->assertStringContainsString('data-rupiah', $html);
        $this->assertStringContainsString('>Rp</span>', $html);
    }

    public function test_edit_form_matches_expected_order_and_grouped_inputs(): void
    {
        [$admin] = $this->fixture();

        $response = $this->actingAs($admin)->get(route('admin.produk'));
        $html = substr($response->getContent(), strpos($response->getContent(), 'id="modal-edit-produk"'));

        $response->assertOk();
        $this->assertTrue(strpos($html, 'id="edit-nama-produk"') < strpos($html, 'id="edit-kategori-hidden"'));
        $this->assertTrue(strpos($html, 'id="edit-kategori-hidden"') < strpos($html, 'id="edit-tipe-produk"'));
        $this->assertTrue(strpos($html, 'id="edit-tipe-produk"') < strpos($html, 'id="edit-harga-dasar"'));
        $this->assertTrue(strpos($html, 'id="edit-harga-dasar"') < strpos($html, 'id="edit-hpp"'));
        $this->assertTrue(strpos($html, 'id="edit-hpp"') < strpos($html, 'id="edit-deskripsi"'));
        $this->assertStringContainsString('data-ribuan-int', $html);
        $this->assertStringContainsString('data-rupiah', $html);
    }

    private function fixture(): array
    {
        $admin = User::whereHas('role', fn ($query) => $query->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($query) => $query->where('status', 'aktif'))
            ->firstOrFail();
        $storeId = StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->value('store_id');
        $categoryId = Category::where('status', 'aktif')->value('category_id');
        SlotGrant::create([
            'store_id' => $storeId,
            'jumlah_slot' => 5,
            'tipe' => SlotGrant::TIPE_MANUAL,
            'keterangan' => 'Slot uji format form produk.',
            'created_by' => $admin->user_id,
        ]);

        return [$admin, $storeId, $categoryId];
    }
}
