<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GudangDropdownSmokeTest extends TestCase
{
    public function test_gudang_pages_render_dropdown_with_assigned_warehouses(): void
    {
        $user = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Gudang'))->firstOrFail();

        $pages = [
            'gudang.dashboard',
            'gudang.stok',
            'gudang.barang-masuk',
            'gudang.barang-keluar',
            'gudang.pemindahan',
            'gudang.pemeriksaan',
            'gudang.stok-rusak',
            'gudang.riwayat-stok',
            'gudang.pelanggan-request',
            'gudang.notifikasi',
            'gudang.profil',
        ];

        foreach ($pages as $route) {
            $res = $this->actingAs($user)->get(route($route));
            $res->assertStatus(200);
            $html = $res->getContent();

            if ($route === 'gudang.dashboard') {
                // Dropdown ganti gudang hanya ada di card "Gudang Aktif" dashboard.
                $this->assertStringContainsString('Gudang Utama Bandung', $html, 'dashboard should show active warehouse name');
                $this->assertStringContainsString('Gudang Cabang Jakarta', $html, 'dashboard should list other assigned warehouse');
                $this->assertStringContainsString('/gudang/ganti', $html, 'dashboard should contain ganti form action');
            }
        }
    }

    public function test_dashboard_pelanggan_request_card_shows_count(): void
    {
        $user = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Gudang'))->firstOrFail();

        $res = $this->actingAs($user)->get(route('gudang.dashboard'));
        $res->assertStatus(200);

        $html = $res->getContent();
        $this->assertStringContainsString('Pelanggan Request', $html);
        $this->assertStringContainsString('pesanan menunggu cek stok', $html);
        // Angka harus sesuai jumlah Order dibayar/diproses milik toko gudang ini.
        $expected = \App\Models\Order::whereIn('status', ['dibayar', 'diproses'])->count();
        $this->assertStringContainsString('>' . $expected . '<', $html, 'card should show request count');
    }

    public function test_switching_warehouse_changes_active_session(): void
    {
        $user = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Gudang'))->firstOrFail();

        $first = $this->actingAs($user)->get(route('gudang.stok'));
        $first->assertStatus(200);

        // Switch to the second warehouse via the ganti route.
        $this->actingAs($user)
            ->post(route('gudang.ganti'), ['warehouse_id' => 2])
            ->assertRedirect();

        $after = $this->actingAs($user)->get(route('gudang.stok'));
        $after->assertStatus(200);
        $this->assertStringContainsString('Gudang Cabang Jakarta', $after->getContent());
    }
}
