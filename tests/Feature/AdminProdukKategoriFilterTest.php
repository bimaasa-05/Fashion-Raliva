<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminProdukKategoriFilterTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Assertion memakai string Indonesia; kunci locale agar deterministik meski .env memakai en.
        app()->setLocale('id');
    }

    public function test_filter_kategori_menampilkan_hanya_produk_kategori_itu(): void
    {
        [$admin, $storeId] = $this->admin();
        $cats = Category::where('status', 'aktif')->orderBy('category_id')->limit(2)->get();
        $this->assertTrue($cats->count() === 2, 'Butuh minimal 2 kategori aktif untuk pengujian.');
        [$catA, $catB] = [$cats[0], $cats[1]];

        $namaA = 'Filter Kat A ' . uniqid();
        $namaB = 'Filter Kat B ' . uniqid();
        $pA = Product::where('store_id', $storeId)->firstOrFail();
        $pA->update(['category_id' => $catA->category_id, 'nama_produk' => $namaA]);
        $pB = Product::where('store_id', $storeId)->where('product_id', '!=', $pA->product_id)->firstOrFail();
        $pB->update(['category_id' => $catB->category_id, 'nama_produk' => $namaB]);

        $this->actingAsFresh($admin)->get(route('admin.produk', ['kategori' => $catA->category_id]))
            ->assertOk()
            ->assertSee($namaA, false)
            ->assertDontSee($namaB, false);
    }

    public function test_tanpa_filter_menampilkan_semua_dan_box_searchable(): void
    {
        [$admin, $storeId] = $this->admin();
        $p = Product::where('store_id', $storeId)->firstOrFail();

        $this->actingAsFresh($admin)->get(route('admin.produk'))
            ->assertOk()
            ->assertSee($p->nama_produk, false)
            ->assertSee('id="box-filter-kategori"', false)
            ->assertSee('id="filter-kategori-search"', false);
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

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }
}
