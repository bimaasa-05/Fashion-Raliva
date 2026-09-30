<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Role;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Isolasi data antar toko untuk Admin Toko: data hasil seeder di toko A
 * tidak boleh muncul di admin yang hanya ditugaskan ke toko B.
 */
class AdminStoreIsolationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_produk_index_hides_other_store_products(): void
    {
        [$admin1, $store1] = $this->admin();
        $produk1 = Product::where('store_id', $store1)->firstOrFail();
        [$admin2, $store2] = $this->otherAdmin($store1);

        $produk2 = $produk1->replicate();
        $produk2->store_id = $store2;
        $produk2->nama_produk = 'Isolasi Toko Dua ' . uniqid();
        $produk2->save();

        // Admin toko 1 tetap melihat produknya sendiri.
        $this->actingAsFresh($admin1)->get(route('admin.produk'))
            ->assertOk()
            ->assertSee($produk1->nama_produk, false);

        // Admin toko 2 tidak melihat produk toko 1, tapi melihat miliknya.
        $this->actingAsFresh($admin2)->get(route('admin.produk'))
            ->assertOk()
            ->assertDontSee($produk1->nama_produk, false)
            ->assertSee($produk2->nama_produk, false);
    }

    public function test_pesanan_customer_dropdown_hides_other_store_customers(): void
    {
        [$admin1, $store1] = $this->admin();
        $cust1 = $this->storeCustomer($store1);
        [$admin2] = $this->otherAdmin($store1);

        $this->actingAsFresh($admin1)->get(route('admin.pesanan'))
            ->assertOk()
            ->assertSee($cust1->email, false);

        $this->actingAsFresh($admin2)->get(route('admin.pesanan'))
            ->assertOk()
            ->assertDontSee($cust1->email, false);
    }

    public function test_customer_index_hides_other_store_customers(): void
    {
        [$admin1, $store1] = $this->admin();
        $cust1 = $this->storeCustomer($store1);
        [$admin2] = $this->otherAdmin($store1);

        $this->actingAsFresh($admin1)->get(route('admin.customer'))
            ->assertOk()
            ->assertSee($cust1->email, false);

        $this->actingAsFresh($admin2)->get(route('admin.customer'))
            ->assertOk()
            ->assertDontSee($cust1->email, false);
    }

    /**
     * @return array{User, int}
     */
    private function admin(): array
    {
        $storeId = (int) StoreStaff::where('status', 'aktif')->value('store_id');
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('store_id', $storeId)->where('status', 'aktif'))
            ->orderBy('user_id')
            ->firstOrFail();

        return [$admin, $storeId];
    }

    /**
     * Admin baru yang hanya ditugaskan ke toko baru (bukan toko seeder).
     *
     * @return array{User, int}
     */
    private function otherAdmin(int $store1): array
    {
        $ownerId = Store::where('store_id', $store1)->value('owner_id');
        $store2 = Store::create([
            'nama_toko' => 'Toko Isolasi Uji ' . uniqid(),
            'owner_id' => $ownerId,
            'status' => Store::STATUS_AKTIF,
        ]);
        $admin2 = User::create([
            'nama_lengkap' => 'Admin Isolasi Uji',
            'email' => 'admin-isolasi-' . uniqid() . '@raliva.test',
            'password' => Hash::make('password'),
            'role_id' => Role::where('nama_role', Role::ADMIN)->value('role_id'),
            'status' => User::STATUS_AKTIF,
        ]);
        StoreStaff::create([
            'store_id' => $store2->store_id,
            'user_id' => $admin2->user_id,
            'tanggal_penugasan' => now(),
            'status' => 'aktif',
        ]);

        return [$admin2->fresh(), (int) $store2->store_id];
    }

    private function storeCustomer(int $storeId): User
    {
        return User::whereHas('role', fn ($q) => $q->where('nama_role', Role::CUSTOMER))
            ->whereHas('orders', fn ($q) => $q->whereIn('store_id', [$storeId]))
            ->orderBy('user_id')
            ->firstOrFail();
    }

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }
}
