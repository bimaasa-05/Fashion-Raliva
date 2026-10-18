<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminLaporanSyncTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Assertion memakai string Indonesia; kunci locale agar deterministik meski .env memakai en.
        app()->setLocale('id');
    }

    private function adminTanpaToko(): User
    {
        $role = Role::where('nama_role', 'Admin')->firstOrFail();

        return User::create([
            'role_id' => $role->role_id,
            'nama_lengkap' => 'Admin Lapor '.Str::random(5),
            'email' => 'admin-lapor-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
    }

    public function test_laporan_empty_assignment_shows_notice(): void
    {
        $res = $this->actingAs($this->adminTanpaToko())->get(route('admin.laporan'));
        $res->assertOk();
        $res->assertSee('belum ditugaskan ke toko aktif', false);
    }

    public function test_laporan_refund_excluded_from_pendapatan(): void
    {
        $ownerRole = Role::where('nama_role', 'Owner')->firstOrFail();
        $owner = User::create([
            'role_id' => $ownerRole->role_id,
            'nama_lengkap' => 'Owner Lapor',
            'email' => 'owner-lapor-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Lapor',
            'alamat' => 'Jl. Lapor No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);
        $admin = $this->adminTanpaToko();
        StoreStaff::create([
            'store_id' => $store->store_id,
            'user_id' => $admin->user_id,
            'status' => 'aktif',
        ]);

        $custRole = Role::where('nama_role', 'Customer')->firstOrFail();
        $cust = User::create([
            'role_id' => $custRole->role_id,
            'nama_lengkap' => 'Cust Lapor',
            'email' => 'cust-lapor-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $checkout = \App\Models\Checkout::create([
            'user_id' => $cust->user_id,
            'subtotal' => 150000,
            'grand_total' => 150000,
            'status' => 'dibayar',
        ]);
        Order::create([
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $store->store_id,
            'nomor_order' => 'ORD-'.uniqid(),
            'status' => Order::STATUS_REFUND,
            'subtotal' => 100000,
            'grand_total' => 100000,
        ]);
        Order::create([
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $store->store_id,
            'nomor_order' => 'ORD-SELESAI-1',
            'status' => Order::STATUS_SELESAI,
            'subtotal' => 50000,
            'grand_total' => 50000,
        ]);

        $res = $this->actingAs($admin)->get(route('admin.laporan'));
        $res->assertOk();
        // Hanya order selesai yang dihitung; order refund dikecualikan.
        $res->assertSee('Rp 50.000', false);
        $res->assertDontSee('belum ditugaskan ke toko aktif', false);
        // Toolbar menampilkan identitas toko yang menjadi cakupan laporan.
        $res->assertSee('Toko Lapor', false);
        // Layout baru: highlight metode + tabel pesanan selesai.
        $res->assertSee('Metode Pembayaran Terbanyak', false);
        $res->assertSee('Pesanan Selesai', false);
        $res->assertSee('ORD-SELESAI-1', false);
    }

    public function test_laporan_tabel_hanya_berisi_pesanan_selesai(): void
    {
        $ownerRole = Role::where('nama_role', 'Owner')->firstOrFail();
        $owner = User::create([
            'role_id' => $ownerRole->role_id,
            'nama_lengkap' => 'Owner Lapor Tabel',
            'email' => 'owner-lapor-tabel-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Lapor Tabel',
            'alamat' => 'Jl. Lapor No. 2',
            'status' => Store::STATUS_AKTIF,
        ]);
        $admin = $this->adminTanpaToko();
        StoreStaff::create([
            'store_id' => $store->store_id,
            'user_id' => $admin->user_id,
            'status' => 'aktif',
        ]);

        $custRole = Role::where('nama_role', 'Customer')->firstOrFail();
        $cust = User::create([
            'role_id' => $custRole->role_id,
            'nama_lengkap' => 'Cust Tabel',
            'email' => 'cust-tabel-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $checkout = \App\Models\Checkout::create([
            'user_id' => $cust->user_id,
            'subtotal' => 175000,
            'grand_total' => 175000,
            'status' => 'dibayar',
        ]);
        Order::create([
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $store->store_id,
            'nomor_order' => 'ORD-TABEL-OK',
            'status' => Order::STATUS_SELESAI,
            'subtotal' => 75000,
            'grand_total' => 75000,
        ]);
        Order::create([
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $store->store_id,
            'nomor_order' => 'ORD-TABEL-REFUND',
            'status' => Order::STATUS_REFUND,
            'subtotal' => 100000,
            'grand_total' => 100000,
        ]);

        $res = $this->actingAs($admin)->get(route('admin.laporan'));
        $res->assertOk();
        $res->assertSee('ORD-TABEL-OK', false);
        $res->assertSee('Cust Tabel', false);
        $res->assertDontSee('ORD-TABEL-REFUND', false);
    }

    public function test_laporan_kpi_mengikuti_filter_tanggal(): void
    {
        $ownerRole = Role::where('nama_role', 'Owner')->firstOrFail();
        $owner = User::create([
            'role_id' => $ownerRole->role_id,
            'nama_lengkap' => 'Owner Filter',
            'email' => 'owner-filter-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Filter',
            'alamat' => 'Jl. Filter No. 3',
            'status' => Store::STATUS_AKTIF,
        ]);
        $admin = $this->adminTanpaToko();
        StoreStaff::create([
            'store_id' => $store->store_id,
            'user_id' => $admin->user_id,
            'status' => 'aktif',
        ]);

        $custRole = Role::where('nama_role', 'Customer')->firstOrFail();
        $cust = User::create([
            'role_id' => $custRole->role_id,
            'nama_lengkap' => 'Cust Filter',
            'email' => 'cust-filter-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $checkout = \App\Models\Checkout::create([
            'user_id' => $cust->user_id,
            'subtotal' => 100000,
            'grand_total' => 100000,
            'status' => 'dibayar',
        ]);

        $lama = Order::create([
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $store->store_id,
            'nomor_order' => 'ORD-FILTER-LAMA',
            'status' => Order::STATUS_SELESAI,
            'subtotal' => 100000,
            'grand_total' => 100000,
        ]);
        $lama->forceFill(['created_at' => now()->subDays(60), 'updated_at' => now()->subDays(60)])->save();

        Order::create([
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $store->store_id,
            'nomor_order' => 'ORD-FILTER-BARU',
            'status' => Order::STATUS_SELESAI,
            'subtotal' => 50000,
            'grand_total' => 50000,
        ]);

        // Default 30 hari: order lama di luar rentang, KPI hanya menghitung order baru.
        $res = $this->actingAs($admin)->get(route('admin.laporan'));
        $res->assertOk();
        $res->assertSee('Rp 50.000', false);
        $res->assertDontSee('Rp 100.000', false);
        $res->assertDontSee('ORD-FILTER-LAMA', false);
        $res->assertSee('ORD-FILTER-BARU', false);

        // Rentang 90 hari: kedua order masuk perhitungan.
        $res = $this->actingAs($admin)->get(route('admin.laporan', [
            'dari' => now()->subDays(89)->toDateString(),
            'sampai' => now()->toDateString(),
        ]));
        $res->assertOk();
        $res->assertSee('Rp 150.000', false);
        $res->assertSee('ORD-FILTER-LAMA', false);
    }
}
