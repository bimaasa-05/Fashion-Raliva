<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class OwnerGudangTest extends TestCase
{
    use DatabaseTransactions;

    public function test_owner_can_create_update_and_toggle_warehouse(): void
    {
        [$owner, $storeId] = $this->owner();

        $create = $this->actingAs($owner)->post(route('owner.gudang.store'), [
            'nama_gudang' => 'Gudang Uji Cilegon',
            'alamat' => 'Jl. Uji No. 1',
            'nomor_telepon' => '081234567890',
        ])->assertSessionHasNoErrors();

        $gudang = Warehouse::where('store_id', $storeId)->where('nama_gudang', 'Gudang Uji Cilegon')->firstOrFail();

        $this->actingAs($owner)->put(route('owner.gudang.update', ['warehouse' => $gudang->warehouse_id]), [
            'nama_gudang' => 'Gudang Uji Revisi',
            'alamat' => 'Jl. Uji No. 2',
            'nomor_telepon' => '081234567891',
        ])->assertSessionHasNoErrors();
        $this->assertSame('Gudang Uji Revisi', $gudang->fresh()->nama_gudang);

        // Nonaktifkan gudang baru (Gudang Utama masih aktif) lalu aktifkan lagi.
        $this->actingAs($owner)->post(route('owner.gudang.toggle', ['warehouse' => $gudang->warehouse_id]))
            ->assertSessionHasNoErrors();
        $this->assertSame(Warehouse::STATUS_NONAKTIF, $gudang->fresh()->status);
        $this->actingAs($owner)->post(route('owner.gudang.toggle', ['warehouse' => $gudang->warehouse_id]))
            ->assertSessionHasNoErrors();
        $this->assertSame(Warehouse::STATUS_AKTIF, $gudang->fresh()->status);
    }

    public function test_owner_cannot_deactivate_last_active_warehouse(): void
    {
        [$owner] = $this->owner();
        $store = \App\Models\Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Uji Solo '.Str::random(6),
            'alamat' => 'Jl. Uji No. 1',
            'status' => 'aktif',
        ]);
        $gudang = Warehouse::create([
            'store_id' => $store->store_id,
            'nama_gudang' => 'Gudang Solo',
            'alamat' => 'Jl. Solo No. 1',
            'status' => Warehouse::STATUS_AKTIF,
        ]);

        $response = $this->actingAs($owner)->post(route('owner.gudang.toggle', ['warehouse' => $gudang->warehouse_id]));

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertSame(Warehouse::STATUS_AKTIF, $gudang->fresh()->status);
    }

    public function test_owner_can_assign_gudang_staff(): void
    {
        [$owner, $storeId] = $this->owner();
        $gudangUser = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::GUDANG))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
        $warehouse = Warehouse::where('store_id', $storeId)->firstOrFail();

        $this->actingAs($owner)->post(route('owner.gudang.staff', ['warehouse' => $warehouse->warehouse_id]), [
            'user_id' => $gudangUser->user_id,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('warehouse_staff', [
            'warehouse_id' => $warehouse->warehouse_id,
            'user_id' => $gudangUser->user_id,
            'status' => 'aktif',
        ]);
    }

    public function test_gudang_page_renders_crud_actions(): void
    {
        [$owner] = $this->owner();

        $response = $this->actingAs($owner)->get(route('owner.gudang'));

        $response->assertOk();
        $response->assertSee('Tambah Gudang', false);
        $response->assertSee('modal-tambah-gudang', false);
    }

    private function owner(): array
    {
        $owner = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::OWNER))
            ->whereHas('ownedStores')
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
        $storeId = $owner->ownedStores()->value('store_id');

        $this->flushSession();

        return [$owner, $storeId];
    }
}
