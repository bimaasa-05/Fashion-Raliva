<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerLoginConsistencyTest extends TestCase
{
    use DatabaseTransactions;

    private function actingAsFresh(User $user): static
    {
        $this->flushSession();

        return $this->actingAs($user);
    }

    private function makeCheckoutAccount(string $email): User
    {
        $roleId = Role::where('nama_role', Role::CUSTOMER)->value('role_id') ?? 1;

        return User::create([
            'nama_lengkap' => 'Pelanggan Guest',
            'email' => $email,
            'password' => Hash::make('Raliva123'),
            'role_id' => $roleId,
            'status' => User::STATUS_AKTIF,
            'email_verified_at' => now(),
        ]);
    }

    public function test_checkout_created_account_can_login_on_fresh_session_under_multi_role(): void
    {
        config(['session.multi_role' => true]);

        $email = 'checkout-'.Str::random(8).'@example.com';
        $this->makeCheckoutAccount($email);

        // "Tab baru": sesi kosong/guest, seperti kredensial disalin dari banner pembayaran.
        $this->flushSession();

        $res = $this->post('/login', [
            'email' => $email,
            'password' => 'Raliva123',
        ]);

        $res->assertStatus(302);
        $this->assertAuthenticated();

        // Password yang tidak sesuai (mis. default lama "password") harus ditolak.
        $this->flushSession();
        $res = $this->post('/login', [
            'email' => $email,
            'password' => 'password',
        ]);

        $res->assertStatus(302);
        $res->assertSessionHasErrors('email');
    }

    public function test_admin_created_customer_uses_default_raliva123_password(): void
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))->firstOrFail();
        $email = 'customer-'.Str::random(8).'@example.com';

        $res = $this->actingAsFresh($admin)->post(route('admin.customer.store'), [
            'nama_lengkap' => 'Budi Customer',
            'email' => $email,
            'nomor_telepon' => '081234567890',
        ]);

        $res->assertStatus(302);

        $customer = User::where('email', $email)->first();
        $this->assertNotNull($customer);
        $this->assertTrue(Hash::check('Raliva123', $customer->password), 'Password default akun customer harus Raliva123.');
    }
}