<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileSmokeTest extends TestCase
{
    use DatabaseTransactions;

    private function roleUsers(): array
    {
        $roles = ['Super Admin', 'Owner', 'Admin', 'Gudang', 'Produksi'];

        $users = [];
        foreach ($roles as $role) {
            $users[$role] = User::whereHas('role', fn ($q) => $q->where('nama_role', $role))->firstOrFail();
        }

        return $users;
    }

    /**
     * Authenticate as the given user with a clean session. The default
     * AuthenticateSession middleware keeps a password hash in the session,
     * which would otherwise log the next actingAs() user out in the same test.
     */
    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }

    public function test_profile_page_renders_for_all_staff_roles(): void
    {
        $routes = [
            'Super Admin' => 'superadmin.profil',
            'Owner' => 'owner.profil',
            'Admin' => 'admin.profil',
            'Gudang' => 'gudang.profil',
            'Produksi' => 'produksi.profil',
        ];

        $users = $this->roleUsers();

        foreach ($routes as $role => $route) {
            $res = $this->actingAsFresh($users[$role])->get(route($route));
            $this->assertSame(200, $res->status(), $role.' -> '.$res->headers->get('Location'));
            $this->assertStringContainsString($users[$role]->nama_lengkap, $res->getContent());
        }
    }

    public function test_update_profile_persists_standard_fields_for_all_staff_roles(): void
    {
        $routes = [
            'Super Admin' => ['superadmin.profil.update', 'PUT'],
            'Owner' => ['owner.profil.update', 'PUT'],
            'Admin' => ['admin.profil.update', 'PUT'],
            'Gudang' => ['gudang.profil.update', 'POST'],
            'Produksi' => ['produksi.profil.update', 'PUT'],
        ];

        $users = $this->roleUsers();

        foreach ($routes as $role => [$route, $verb]) {
            $user = $users[$role];

            $payload = [
                'nama_lengkap' => $user->nama_lengkap,
                'email' => $user->email,
                'nomor_telepon' => '081299998888',
                'gender' => 'male',
                'tanggal_lahir' => '1990-01-01',
            ];

            $res = $this->actingAsFresh($user)->call($verb, route($route), $payload);
            $this->assertSame(302, $res->status(), $role.' -> '.$res->headers->get('Location'));

            $this->assertDatabaseHas('users', [
                'user_id' => $user->user_id,
                'gender' => 'male',
                'tanggal_lahir' => '1990-01-01',
                'nomor_telepon' => '081299998888',
            ]);
        }
    }

    public function test_update_password_wrong_current_password_is_rejected_for_all_staff_roles(): void
    {
        $routes = [
            'Super Admin' => 'superadmin.profil.password',
            'Owner' => 'owner.profil.password',
            'Admin' => 'admin.profil.password',
            'Gudang' => 'gudang.profil.password',
            'Produksi' => 'produksi.profil.password',
        ];

        $users = $this->roleUsers();

        foreach ($routes as $role => $route) {
            $user = $users[$role];

            $res = $this->actingAsFresh($user)->call('PUT', route($route), [
                'password_lama' => 'wrong-password',
                'password_baru' => 'Raliva2026!',
                'password_baru_confirmation' => 'Raliva2026!',
            ]);
            $res->assertStatus(302);
            $res->assertSessionHasErrors('password_lama');
        }
    }

    public function test_changing_password_keeps_current_session_authenticated(): void
    {
        $user = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))->firstOrFail();
        $user->update(['password' => 'KnownPass1']);

        $res = $this->actingAsFresh($user)->put(route('superadmin.profil.password'), [
            'password_lama' => 'KnownPass1',
            'password_baru' => 'Raliva2026!',
            'password_baru_confirmation' => 'Raliva2026!',
        ]);

        $res->assertStatus(302);
        $this->assertTrue(Hash::check('Raliva2026!', $user->fresh()->password));

        $after = $this->get(route('superadmin.profil'));
        $this->assertSame(200, $after->status(), 'Sesi ter-logout -> '.$after->headers->get('Location'));
    }

    public function test_photo_upload_stored_on_public_disk_not_public_dir(): void
    {
        $user = $this->roleUsers()['Gudang'];
        $file = UploadedFile::fake()->image('foto.jpg', 120, 120);

        $res = $this->actingAsFresh($user)->post(route('gudang.profil.update'), [
            'nama_lengkap' => $user->nama_lengkap,
            'email' => $user->email,
            'foto_profil' => $file,
        ]);

        $res->assertStatus(302);

        $fresh = $user->fresh();
        $this->assertNotEmpty($fresh->foto_profil);
        $this->assertTrue(Storage::disk('public')->exists($fresh->foto_profil), 'File harus disimpan di disk public (storage/app/public/profil).');
        $this->assertFalse(file_exists(public_path($fresh->foto_profil)), 'File TIDAK boleh berada di public/profil.');
        $this->assertSame(asset('storage/' . $fresh->foto_profil), $fresh->foto_profil_url);

        Storage::disk('public')->delete($fresh->foto_profil);
    }
}
