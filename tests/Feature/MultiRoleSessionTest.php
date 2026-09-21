<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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

        $customer = $this->activeUser(Role::CUSTOMER);
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