<?php

namespace Tests\Feature;

use App\Http\Middleware\AreaSession;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Tests\TestCase;

class MultiRoleSessionTest extends TestCase
{
    use DatabaseTransactions;

    private function activeUser(string $role): User
    {
        return User::whereHas('role', fn ($q) => $q->where('nama_role', $role))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
    }

    private function activeCustomer(): User
    {
        $customer = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::CUSTOMER))
            ->where('status', User::STATUS_AKTIF)
            ->first();

        if ($customer) {
            return $customer;
        }

        return User::create([
            'role_id' => Role::where('nama_role', Role::CUSTOMER)->value('role_id'),
            'nama_lengkap' => 'Customer Uji Multi Role',
            'email' => 'customer-multi-'.uniqid().'@test.local',
            'password' => bcrypt('Raliva2026!'),
            'status' => User::STATUS_AKTIF,
            'email_verified_at' => now(),
        ]);
    }

    public function test_public_auth_routes_keep_default_session_cookie_with_area_referer(): void
    {
        config(['session.multi_role' => true]);

        foreach (['login', 'register', 'forgot-password', 'forgot-password/otp'] as $path) {
            config(['session.cookie' => 'raliva_default_session']);

            $request = Request::create('/'.$path, 'GET', server: [
                'HTTP_REFERER' => route('owner.dashboard'),
            ]);

            (new AreaSession)->handle($request, fn () => response('ok'));

            $this->assertSame(
                'raliva_default_session',
                config('session.cookie'),
                "Path {$path} tidak boleh memakai cookie area."
            );
        }
    }

    public function test_area_path_still_uses_area_session_cookie(): void
    {
        config(['session.multi_role' => true, 'session.cookie' => 'raliva_default_session']);

        $request = Request::create('/owner/dashboard', 'GET');
        (new AreaSession)->handle($request, fn () => response('ok'));

        $this->assertSame('raliva_owner_session', config('session.cookie'));
    }

    public function test_login_wrong_password_redirects_back_to_login_with_error(): void
    {
        config(['session.multi_role' => true]);

        $res = $this->withHeader('Referer', route('owner.dashboard'))->post(route('login'), [
            'email' => 'wrong-'.uniqid().'@example.com',
            'password' => 'wrong-password',
        ]);

        $res->assertRedirect(route('login'));
        $res->assertSessionHasErrors('email');
    }

    public function test_login_failure_preserves_safe_redirect(): void
    {
        config(['session.multi_role' => true]);

        $res = $this->post(route('login'), [
            'email' => 'wrong-'.uniqid().'@example.com',
            'password' => 'wrong-password',
            'redirect' => '/customer/chart',
        ]);

        $res->assertRedirect(route('login', ['redirect' => '/customer/chart']));
        $res->assertSessionHasErrors('email');
    }

    public function test_staff_login_sets_area_cookie_when_multi_role_enabled(): void
    {
        config(['session.multi_role' => true]);

        $owner = $this->activeUser(Role::OWNER);
        $owner->update(['password' => 'Raliva2026!']);

        $res = $this->post(route('login'), [
            'email' => $owner->email,
            'password' => 'Raliva2026!',
        ]);

        $res->assertRedirect(route('owner.dashboard'));
        $res->assertCookie('raliva_owner_session');
        $res->assertCookieMissing('raliva_admin_session');
    }

    public function test_customer_login_keeps_default_cookie_when_multi_role_enabled(): void
    {
        config(['session.multi_role' => true]);

        $customer = $this->activeCustomer();
        $customer->update(['password' => 'Raliva2026!']);

        $res = $this->post(route('login'), [
            'email' => $customer->email,
            'password' => 'Raliva2026!',
        ]);

        $res->assertRedirect(route('customer.home'));
        $res->assertCookie(config('session.cookie'));
    }

    public function test_staff_login_keeps_default_cookie_when_multi_role_disabled(): void
    {
        config(['session.multi_role' => false]);

        $admin = $this->activeUser(Role::ADMIN);
        $admin->update(['password' => 'Raliva2026!']);

        $res = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'Raliva2026!',
        ]);

        $res->assertRedirect(route('admin.dashboard'));
        $res->assertCookie(config('session.cookie'));
        $res->assertCookieMissing('raliva_admin_session');
    }
}