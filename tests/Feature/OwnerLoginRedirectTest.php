<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OwnerLoginRedirectTest extends TestCase
{
    use DatabaseTransactions;

    public function test_owner_tanpa_toko_diarahkan_ke_pengajuan_toko(): void
    {
        $owner = $this->makeOwner();

        $response = $this->post(route('login'), [
            'email' => $owner->email,
            'password' => 'Owner2026!',
        ]);

        $response->assertRedirect(route('owner.pengajuan-toko'));
        $this->assertAuthenticatedAs($owner);
    }

    public function test_owner_dengan_toko_pending_diarahkan_ke_pengajuan_toko(): void
    {
        $owner = $this->makeOwner();
        $this->makeStore($owner, Store::STATUS_PENDING);

        $this->post(route('login'), [
            'email' => $owner->email,
            'password' => 'Owner2026!',
        ])->assertRedirect(route('owner.pengajuan-toko'));
    }

    public function test_owner_dengan_toko_ditolak_diarahkan_ke_pengajuan_toko(): void
    {
        $owner = $this->makeOwner();
        $this->makeStore($owner, Store::STATUS_DITOLAK);

        $this->post(route('login'), [
            'email' => $owner->email,
            'password' => 'Owner2026!',
        ])->assertRedirect(route('owner.pengajuan-toko'));
    }

    public function test_owner_dengan_toko_aktif_diarahkan_ke_dashboard(): void
    {
        $owner = $this->makeOwner();
        $this->makeStore($owner, Store::STATUS_AKTIF);

        $this->post(route('login'), [
            'email' => $owner->email,
            'password' => 'Owner2026!',
        ])->assertRedirect(route('owner.dashboard'));
    }

    public function test_owner_dengan_toko_nonaktif_tetap_diarahkan_ke_dashboard(): void
    {
        $owner = $this->makeOwner();
        $this->makeStore($owner, Store::STATUS_NONAKTIF);

        $this->post(route('login'), [
            'email' => $owner->email,
            'password' => 'Owner2026!',
        ])->assertRedirect(route('owner.dashboard'));
    }

    private function makeOwner(): User
    {
        return User::factory()->create([
            'role_id' => Role::query()->where('nama_role', Role::OWNER)->value('role_id'),
            'password' => 'Owner2026!',
            'status' => User::STATUS_AKTIF,
        ]);
    }

    private function makeStore(User $owner, string $status): Store
    {
        return Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Uji '.uniqid(),
            'status' => $status,
        ]);
    }
}
