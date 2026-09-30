<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SosmedPlatform;
use App\Models\Store;
use App\Models\StoreSocial;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SosmedTest extends TestCase
{
    use DatabaseTransactions;

    private function superAdmin(): User
    {
        return User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))->firstOrFail();
    }

    private function makeOwner(string $suffix): array
    {
        $role = Role::where('nama_role', 'Owner')->firstOrFail();
        $owner = User::create([
            'role_id' => $role->role_id,
            'nama_lengkap' => 'Owner Sosmed '.$suffix,
            'email' => 'owner-sosmed-'.strtolower($suffix).'-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Sosmed '.$suffix,
            'alamat' => 'Jl. Sosmed No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);

        return [$owner, $store];
    }

    public function test_superadmin_can_add_and_toggle_platform(): void
    {
        $this->actingAs($this->superAdmin());

        $res = $this->post(route('superadmin.pengaturan-sistem.sosmed.store'), ['nama_platform' => 'Threads']);
        $res->assertRedirect();
        $platform = SosmedPlatform::where('nama_platform', 'Threads')->firstOrFail();
        $this->assertSame('aktif', $platform->status);

        $dup = $this->post(route('superadmin.pengaturan-sistem.sosmed.store'), ['nama_platform' => 'Threads']);
        $dup->assertSessionHasErrors('nama_platform');

        $tog = $this->post(route('superadmin.pengaturan-sistem.sosmed.toggle', $platform));
        $tog->assertRedirect();
        $this->assertSame('nonaktif', $platform->fresh()->status);
    }

    public function test_owner_can_add_platform_and_custom_social(): void
    {
        [$owner] = $this->makeOwner('A');
        $this->actingAs($owner);

        $platform = SosmedPlatform::firstOrCreate(['nama_platform' => 'TikTok'], ['status' => 'aktif']);

        $res = $this->post(route('owner.data-toko.sosmed.store'), [
            'sosmed_platform_id' => $platform->sosmed_platform_id,
            'url' => 'https://tiktok.com/@tokoa',
        ]);
        $res->assertRedirect();

        $dup = $this->post(route('owner.data-toko.sosmed.store'), [
            'sosmed_platform_id' => $platform->sosmed_platform_id,
            'url' => 'https://tiktok.com/@tokoa2',
        ]);
        $dup->assertSessionHas('error');

        $custom = $this->post(route('owner.data-toko.sosmed.store'), [
            'nama_custom' => 'Threads',
            'url' => 'https://threads.net/@tokoa',
        ]);
        $custom->assertRedirect();
        $this->assertDatabaseHas('store_socials', ['nama_custom' => 'Threads']);

        $bad = $this->post(route('owner.data-toko.sosmed.store'), [
            'nama_custom' => 'Blog',
            'url' => 'bukan-url',
        ]);
        $bad->assertSessionHasErrors('url');
    }

    public function test_owner_can_delete_own_social_but_not_others(): void
    {
        [$ownerA, $storeA] = $this->makeOwner('B');
        [$ownerB, $storeB] = $this->makeOwner('C');

        $other = StoreSocial::create([
            'store_id' => $storeB->store_id,
            'nama_custom' => 'Milik B',
            'url' => 'https://example.com/b',
        ]);
        $mine = StoreSocial::create([
            'store_id' => $storeA->store_id,
            'nama_custom' => 'Milik A',
            'url' => 'https://example.com/a',
        ]);

        $this->actingAs($ownerA);
        $this->delete(route('owner.data-toko.sosmed.destroy', $other))->assertRedirect();
        $this->assertNotNull($other->fresh());
        $this->delete(route('owner.data-toko.sosmed.destroy', $mine))->assertRedirect();
        $this->assertNull(StoreSocial::find($mine->store_social_id));
    }

    public function test_store_about_shows_socials(): void
    {
        [$owner, $store] = $this->makeOwner('D');
        StoreSocial::create([
            'store_id' => $store->store_id,
            'nama_custom' => 'TikTok',
            'url' => 'https://tiktok.com/@tokod',
        ]);

        $res = $this->get(route('customer.shop.store.about', $store->store_id));
        $res->assertOk();
        $res->assertSee('Social Media', false);
        $res->assertSee('https://tiktok.com/@tokod', false);
    }
}
