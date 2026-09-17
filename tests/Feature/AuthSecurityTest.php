<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_is_rate_limited_after_repeated_failures(): void
    {
        $email = 'throttle-'.uniqid().'@raliva.test';

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login'), ['email' => $email, 'password' => 'wrong-password'])
                ->assertSessionHasErrors('email');
        }

        $res = $this->post(route('login'), ['email' => $email, 'password' => 'wrong-password']);

        $res->assertSessionHasErrors([
            'email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam 1 menit.',
        ]);
    }

    public function test_register_rejects_weak_password(): void
    {
        $email = 'register-'.uniqid().'@raliva.test';

        $res = $this->post(route('register'), [
            'nama_lengkap' => 'Calon User',
            'email' => $email,
            'password' => 'weakpassword',
            'password_confirmation' => 'weakpassword',
            'role' => 'customer',
            'terms' => '1',
        ]);

        $res->assertSessionHasErrors(['password' => 'Password harus mengandung minimal 1 huruf kapital dan 1 angka.']);
        $this->assertDatabaseMissing('users', ['email' => $email]);
    }

    public function test_register_accepts_strong_password(): void
    {
        $email = 'register-'.uniqid().'@raliva.test';

        $res = $this->post(route('register'), [
            'nama_lengkap' => 'Calon User',
            'email' => $email,
            'password' => 'Raliva2026!',
            'password_confirmation' => 'Raliva2026!',
            'role' => 'customer',
            'terms' => '1',
        ]);

        $res->assertRedirect(route('login'));
        $res->assertSessionHas('success');

        $user = User::where('email', $email)->firstOrFail();
        $this->assertTrue(Hash::check('Raliva2026!', $user->password));
        $this->assertSame(Role::CUSTOMER, $user->role->nama_role);
    }
}
