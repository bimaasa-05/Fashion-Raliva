<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class WelcomeNotificationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        app()->setLocale('id');

        config([
            'services.google.client_id' => 'test-client-id',
            'services.google.client_secret' => 'test-client-secret',
            'services.google.redirect' => 'http://localhost:8000/auth/google/callback',
        ]);

        foreach ([Role::CUSTOMER, Role::OWNER, Role::ADMIN, Role::PRODUKSI, Role::GUDANG, Role::SUPER_ADMIN] as $role) {
            $this->roleId($role);
        }
    }

    public function test_register_customer_membuat_welcome_notification(): void
    {
        $email = 'welcome-customer-'.Str::random(8).'@example.com';

        $this->post(route('register'), [
            'nama_lengkap' => 'Customer Welcome',
            'email' => $email,
            'password' => 'Raliva2026!',
            'password_confirmation' => 'Raliva2026!',
            'role' => 'customer',
            'terms' => '1',
        ])->assertRedirect(route('login'));

        $user = User::where('email', $email)->firstOrFail();
        $notification = $this->welcomeFor($user);

        $this->assertSame(route('customer.home'), $notification->url);
        $this->assertSame(__('Welcome to :brand!', ['brand' => brand()]), $notification->judul);
        $this->assertSame(__('Your account is ready. Start exploring the latest collection from our ateliers.'), $notification->pesan);
        $this->assertNull($notification->dibaca_pada);
    }

    public function test_register_owner_membuat_welcome_notification_ke_dashboard(): void
    {
        $email = 'welcome-owner-'.Str::random(8).'@example.com';

        $this->post(route('register'), [
            'nama_lengkap' => 'Owner Welcome',
            'email' => $email,
            'password' => 'Raliva2026!',
            'password_confirmation' => 'Raliva2026!',
            'role' => 'owner',
            'terms' => '1',
        ])->assertRedirect(route('login'));

        $user = User::where('email', $email)->firstOrFail();
        $notification = $this->welcomeFor($user);

        $this->assertSame(route('owner.dashboard'), $notification->url);
        $this->assertSame(__('Your business owner account is ready. Complete your store application from the dashboard.'), $notification->pesan);
    }

    public function test_google_user_baru_membuat_welcome_notification(): void
    {
        $email = 'google-welcome-'.Str::random(8).'@example.com';

        $this->mockGoogleUser([
            'id' => 'google-'.Str::random(12),
            'name' => 'Google Welcome',
            'email' => $email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('customer.home'));

        $user = User::where('email', $email)->firstOrFail();
        $notification = $this->welcomeFor($user);

        $this->assertSame(route('customer.home'), $notification->url);
        $this->assertSame(__('Your account is ready. Start exploring the latest collection from our ateliers.'), $notification->pesan);
    }

    public function test_google_user_lama_tidak_mendapat_welcome_baru(): void
    {
        $user = $this->makeUser(Role::CUSTOMER);

        $this->mockGoogleUser([
            'id' => 'google-'.Str::random(12),
            'email' => $user->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('customer.home'));

        $this->assertSame(0, Notification::where('user_id', $user->user_id)->count());
    }

    public function test_owner_menambah_karyawan_membuat_welcome_dan_notifikasi_owner(): void
    {
        $owner = $this->makeUser(Role::OWNER);
        $store = $this->makeStore($owner);
        $email = 'karyawan-'.Str::random(8).'@example.com';

        $this->actingAs($owner)->post(route('owner.karyawan.store'), [
            'nama_lengkap' => 'Admin Toko',
            'email' => $email,
            'nomor_telepon' => '081234567890',
            'password' => 'Karyawan2026!',
            'role' => 'admin',
        ])->assertRedirect(route('owner.karyawan'));

        $employee = User::where('email', $email)->firstOrFail();
        $notification = $this->welcomeFor($employee);

        $this->assertSame(route('admin.dashboard'), $notification->url);
        $this->assertSame($owner->user_id, $notification->aktor_id);
        $this->assertStringContainsString('Admin', $notification->pesan);
        $this->assertStringContainsString($store->nama_toko, $notification->pesan);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $owner->user_id,
            'judul' => 'Karyawan Ditambahkan',
        ]);
    }

    public function test_superadmin_membuat_user_membuat_welcome_notification(): void
    {
        $superAdmin = $this->makeUser(Role::SUPER_ADMIN);
        $email = 'admin-baru-'.Str::random(8).'@example.com';

        $this->actingAs($superAdmin)->post(route('superadmin.manajemen-pengguna.store'), [
            'nama_lengkap' => 'Admin Baru',
            'email' => $email,
            'password' => 'Admin2026!',
            'password_confirmation' => 'Admin2026!',
            'role_id' => $this->roleId(Role::ADMIN),
            'nomor_telepon' => '081234567891',
            'status' => User::STATUS_AKTIF,
        ])->assertStatus(302);

        $user = User::where('email', $email)->firstOrFail();
        $notification = $this->welcomeFor($user);

        $this->assertSame(route('admin.dashboard'), $notification->url);
        $this->assertSame($superAdmin->user_id, $notification->aktor_id);
        $this->assertStringContainsString('Admin', $notification->pesan);
    }

    public function test_admin_membuat_customer_tanpa_password_di_notifikasi(): void
    {
        $admin = $this->makeUser(Role::ADMIN);
        $email = 'customer-admin-'.Str::random(8).'@example.com';

        $this->actingAs($admin)->post(route('admin.customer.store'), [
            'nama_lengkap' => 'Customer Admin',
            'email' => $email,
            'nomor_telepon' => '081234567892',
        ])->assertStatus(302);

        $customer = User::where('email', $email)->firstOrFail();
        $notification = $this->welcomeFor($customer);

        $this->assertSame(route('customer.home'), $notification->url);
        $this->assertSame($admin->user_id, $notification->aktor_id);
        $this->assertStringContainsString($admin->nama_lengkap, $notification->pesan);
        $this->assertStringNotContainsString('Raliva123', $notification->pesan);
    }

    public function test_halaman_notifikasi_customer_memakai_url_notifikasi(): void
    {
        $customer = $this->makeUser(Role::CUSTOMER);

        $notification = Notification::create([
            'user_id' => $customer->user_id,
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => 'Info',
            'pesan' => 'Info',
            'url' => route('customer.home'),
        ]);

        $this->actingAs($customer)
            ->get(route('customer.notifications'))
            ->assertOk()
            ->assertSee("handleNotif(this, {$notification->notification_id}, '".route('customer.home')."')", false);
    }

    public function test_drawer_customer_menampilkan_welcome_toast_dan_item_notifikasi(): void
    {
        $customer = $this->makeUser(Role::CUSTOMER);

        $notification = Notification::create([
            'user_id' => $customer->user_id,
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => __('Welcome to :brand!', ['brand' => brand()]),
            'pesan' => __('Your account is ready. Start exploring the latest collection from our ateliers.'),
            'url' => route('customer.home'),
        ]);

        $this->actingAs($customer)
            ->get(route('customer.notifications'))
            ->assertOk()
            ->assertSee(route('customer.notifications'), false)
            ->assertSee('ralivaWelcomeShown_'.$notification->notification_id, false)
            ->assertSee(__('Welcome to :brand!', ['brand' => brand()]));
    }

    private function makeUser(string $role, array $overrides = []): User
    {
        return User::create(array_merge([
            'nama_lengkap' => 'User '.Str::random(6),
            'email' => 'welcome-'.Str::random(10).'@example.com',
            'password' => 'Raliva2026!',
            'role_id' => $this->roleId($role),
            'status' => User::STATUS_AKTIF,
            'email_verified_at' => now(),
        ], $overrides));
    }

    private function makeStore(User $owner): Store
    {
        return Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko '.Str::random(6),
            'alamat' => 'Jl. Uji No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);
    }

    private function roleId(string $role): int
    {
        return (int) Role::query()->firstOrCreate(
            ['nama_role' => $role],
            ['deskripsi' => $role, 'status' => 'aktif']
        )->role_id;
    }

    private function welcomeFor(User $user): Notification
    {
        return Notification::query()
            ->where('user_id', $user->user_id)
            ->where('tipe', Notification::TIPE_SISTEM)
            ->where('judul', __('Welcome to :brand!', ['brand' => brand()]))
            ->firstOrFail();
    }

    private function mockGoogleUser(array $attributes = []): void
    {
        $googleUser = SocialiteUser::fake(array_merge([
            'id' => 'google-'.Str::random(12),
            'name' => 'Google User',
            'email' => 'google-'.Str::random(12).'@example.com',
            'email_verified' => true,
        ], $attributes));

        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->once()->andReturn($googleUser);

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturn($provider);
    }
}
