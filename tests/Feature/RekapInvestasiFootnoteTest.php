<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RekapInvestasiFootnoteTest extends TestCase
{
    use DatabaseTransactions;

    public function test_owner_rekap_explains_investasi_source(): void
    {
        $ownerRole = Role::where('nama_role', 'Owner')->firstOrFail();
        $owner = User::create([
            'role_id' => $ownerRole->role_id,
            'nama_lengkap' => 'Owner Rekap',
            'email' => 'owner-rekap-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Rekap',
            'alamat' => 'Jl. Rekap No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);

        $res = $this->actingAs($owner)->get(route('owner.rekap-karyawan', ['role' => 'owner']));
        $res->assertOk();
        $res->assertSee('bukan dana investor luar', false);
    }

    public function test_aov_toko_includes_pending_dibayar_selesai(): void
    {
        $ownerRole = Role::where('nama_role', 'Owner')->firstOrFail();
        $owner = User::create([
            'role_id' => $ownerRole->role_id,
            'nama_lengkap' => 'Owner AOV',
            'email' => 'owner-aov-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko AOV',
            'alamat' => 'Jl. AOV No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);
        $custRole = Role::where('nama_role', 'Customer')->firstOrFail();
        $cust = User::create([
            'role_id' => $custRole->role_id,
            'nama_lengkap' => 'Cust AOV',
            'email' => 'cust-aov-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $checkout = \App\Models\Checkout::create([
            'user_id' => $cust->user_id,
            'subtotal' => 600000,
            'grand_total' => 600000,
            'status' => 'dibayar',
        ]);
        $mk = function (string $status, int $total) use ($checkout, $store) {
            \App\Models\Order::create([
                'checkout_id' => $checkout->checkout_id,
                'store_id' => $store->store_id,
                'nomor_order' => 'AOV-'.$status.'-'.uniqid(),
                'status' => $status,
                'subtotal' => $total,
                'grand_total' => $total,
            ]);
        };
        $mk(\App\Models\Order::STATUS_PENDING_PAYMENT, 100000);
        $mk(\App\Models\Order::STATUS_DIBAYAR, 200000);
        $mk(\App\Models\Order::STATUS_SELESAI, 300000);
        $mk(\App\Models\Order::STATUS_DIBATALKAN, 900000);

        $aov = app(\App\Services\KaryawanReportService::class)->aovToko([$store->store_id]);
        $this->assertSame(200000.0, $aov);

        $res = $this->actingAs($owner)->get(route('owner.rekap-karyawan', ['role' => 'owner']));
        $res->assertOk();
        $res->assertSee('AOV Toko (Pending + Dibayar + Selesai)', false);
        $res->assertSee('Rp 200.000', false);
    }
}
