<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Warehouse;
use Tests\TestCase;

class GudangDropdownSmokeTest extends TestCase
{
    /**
     * Ambil user Gudang yang ditugaskan minimal ke satu gudang aktif,
     * agar test tidak bergantung pada id user/penugasan hardcode dari seed.
     */
    private function gudangUser(): User
    {
        return User::whereHas('role', fn ($q) => $q->where('nama_role', 'Gudang'))
            ->whereHas('assignedWarehouses', fn ($q) => $q->where('warehouses.status', Warehouse::STATUS_AKTIF))
            ->with(['assignedWarehouses' => fn ($q) => $q->where('warehouses.status', Warehouse::STATUS_AKTIF)->orderBy('nama_gudang')])
            ->firstOrFail();
    }

    public function test_gudang_pages_render_dropdown_with_assigned_warehouses(): void
    {
        $user = $this->gudangUser();
        $warehouses = $user->assignedWarehouses->pluck('nama_gudang');

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
                $this->assertNotEmpty($warehouses, 'gudang user should have assigned warehouses');
                $this->assertStringContainsString($warehouses->first(), $html, 'dashboard should show active warehouse name');
                if ($warehouses->count() > 1) {
                    $this->assertStringContainsString($warehouses->last(), $html, 'dashboard should list other assigned warehouse');
                }
                $this->assertStringContainsString('/gudang/ganti', $html, 'dashboard should contain ganti form action');
            }
        }
    }

    public function test_dashboard_pelanggan_request_card_shows_count(): void
    {
        $user = $this->gudangUser();

        $res = $this->actingAs($user)->get(route('gudang.dashboard'));
        $res->assertStatus(200);

        $html = $res->getContent();
        $this->assertStringContainsString('Pelanggan Request', $html);
        $this->assertStringContainsString('pesanan menunggu cek stok', $html);
        // Angka harus sesuai jumlah Order dibayar/diproses milik toko gudang ini.
        $expected = Order::whereIn('status', ['dibayar', 'diproses'])->count();
        $this->assertStringContainsString('>'.$expected.'<', $html, 'card should show request count');
    }

    public function test_switching_warehouse_changes_active_session(): void
    {
        $user = $this->gudangUser();
        $warehouses = $user->assignedWarehouses;

        $this->assertGreaterThanOrEqual(2, $warehouses->count(), 'gudang user should be assigned to 2+ warehouses');

        $first = $this->actingAs($user)->get(route('gudang.stok'));
        $first->assertStatus(200);
        $this->assertStringContainsString($warehouses->first()->nama_gudang, $first->getContent());

        // Pindah ke gudang kedua yang ditugaskan.
        $second = $warehouses[1];
        $this->actingAs($user)
            ->post(route('gudang.ganti'), ['warehouse_id' => $second->warehouse_id])
            ->assertRedirect();

        $after = $this->actingAs($user)->get(route('gudang.stok'));
        $after->assertStatus(200);
        $this->assertStringContainsString($second->nama_gudang, $after->getContent());
    }
}
