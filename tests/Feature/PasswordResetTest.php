<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use DatabaseTransactions;

    private function makeUser(string $password = 'OldPassword1'): User
    {
        $role = Role::where('nama_role', Role::CUSTOMER)->firstOrFail();

        return User::create([
            'nama_lengkap' => 'Test Reset',
            'email' => 'reset-'.uniqid().'@raliva.test',
            'password' => Hash::make($password),
            'role_id' => $role->role_id,
            'status' => User::STATUS_AKTIF,
            'email_verified_at' => now(),
        ]);
    }

    public function test_forgot_password_page_renders(): void
    {
        $res = $this->get(route('password.request'));

        $res->assertStatus(200);
        $res->assertSee(route('password.email'), false);
    }

    public function test_reset_password_page_renders_with_token(): void
    {
        $res = $this->get(route('password.reset', [
            'token' => 'dummy-token',
            'email' => 'someone@raliva.test',
        ]));

        $res->assertStatus(200);
        $res->assertSee('dummy-token', false);
        $res->assertSee(route('password.update'), false);
    }

    public function test_reset_link_request_creates_token_and_sends_notification(): void
    {
        Notification::fake();

        $user = $this->makeUser();

        $res = $this->post(route('password.email'), ['email' => $user->email]);

        $res->assertSessionHas('status');
        $this->assertDatabaseHas('password_reset_tokens', ['email' => $user->email]);
        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_valid_token_resets_password_and_consumes_token(): void
    {
        $user = $this->makeUser();
        $token = Password::broker()->createToken($user);

        $res = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'Raliva2026!',
            'password_confirmation' => 'Raliva2026!',
        ]);

        $res->assertRedirect(route('login'));
        $res->assertSessionHas('success');
        $this->assertTrue(Hash::check('Raliva2026!', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
    }

    public function test_invalid_token_is_rejected_and_password_unchanged(): void
    {
        $user = $this->makeUser();

        $res = $this->post(route('password.update'), [
            'token' => 'not-a-real-token',
            'email' => $user->email,
            'password' => 'Raliva2026!',
            'password_confirmation' => 'Raliva2026!',
        ]);

        $res->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('OldPassword1', $user->fresh()->password));
    }

    public function test_reset_password_enforces_policy(): void
    {
        $user = $this->makeUser();
        $token = Password::broker()->createToken($user);

        $res = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'weakpassword',
            'password_confirmation' => 'weakpassword',
        ]);

        $res->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('OldPassword1', $user->fresh()->password));
    }
}
