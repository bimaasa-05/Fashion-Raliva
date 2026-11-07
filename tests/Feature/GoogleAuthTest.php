<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
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
    }

    public function test_route_redirect_google_memakai_redirect_uri_yang_dikonfigurasi(): void
    {
        $response = $this->get(route('auth.google.redirect'));

        $response->assertRedirect();

        $location = (string) $response->headers->get('Location');

        $this->assertStringContainsString('accounts.google.com', $location);
        $this->assertStringContainsString('client_id=test-client-id', $location);
        $this->assertStringContainsString(
            urlencode('http://localhost:8000/auth/google/callback'),
            $location
        );
    }

    public function test_route_redirect_google_ditolak_saat_konfigurasi_kosong(): void
    {
        config(['services.google.client_id' => null]);

        $this->get(route('auth.google.redirect'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');
    }

    public function test_route_redirect_google_menyimpan_intent_role(): void
    {
        $this->get(route('auth.google.redirect', ['intent' => 'owner']))
            ->assertSessionHas('oauth_role_intent', 'owner');

        $this->get(route('auth.google.redirect', ['intent' => 'customer']))
            ->assertSessionHas('oauth_role_intent', 'customer');
    }

    public function test_route_redirect_google_memindahkan_ke_origin_callback(): void
    {
        $response = $this->get('http://127.0.0.1:8000/auth/google/redirect?intent=owner&redirect=%2Fcustomer%2Fchart');

        $response->assertRedirect();

        $location = (string) $response->headers->get('Location');
        $parts = parse_url($location);
        $query = [];
        parse_str($parts['query'] ?? '', $query);

        $this->assertSame('http', $parts['scheme'] ?? null);
        $this->assertSame('localhost', $parts['host'] ?? null);
        $this->assertSame(8000, $parts['port'] ?? null);
        $this->assertSame('/auth/google/redirect', $parts['path'] ?? null);
        $this->assertSame('owner', $query['intent'] ?? null);
        $this->assertSame('/customer/chart', $query['redirect'] ?? null);
        $response->assertSessionMissing('oauth_role_intent');
    }

    public function test_route_redirect_google_menulis_ulang_tujuan_absolute_ke_origin_callback(): void
    {
        $response = $this->get(
            'http://127.0.0.1:8000/auth/google/redirect?intent=owner&redirect='.
            urlencode('http://127.0.0.1:8000/customer/chart')
        );

        $response->assertRedirect();

        $location = (string) $response->headers->get('Location');
        $query = [];
        parse_str((string) parse_url($location, PHP_URL_QUERY), $query);

        $this->assertSame('http://localhost:8000/customer/chart', $query['redirect'] ?? null);
    }

    public function test_route_redirect_google_tidak_memakai_cookie_area(): void
    {
        config(['session.multi_role' => true]);

        $response = $this->get(route('auth.google.redirect', [
            'intent' => 'owner',
            'redirect' => '/owner/dashboard',
        ]));

        $response->assertRedirect();

        $cookieNames = array_map(
            static fn ($cookie) => $cookie->getName(),
            $response->headers->getCookies()
        );

        $this->assertContains(config('session.cookie'), $cookieNames);
        $this->assertNotContains('raliva_owner_session', $cookieNames);
    }

    public function test_callback_google_menangani_state_tidak_cocok(): void
    {
        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->once()->andThrow(new InvalidStateException);
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);

        $this->get(route('auth.google.callback', ['state' => 'state-palsu']))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors([
                'email' => __('Sesi login Google kedaluwarsa. Silakan coba lagi.'),
            ]);

        $this->assertGuest();
    }

    public function test_login_google_membuat_customer_baru(): void
    {
        $email = 'google-baru-'.Str::random(8).'@example.com';

        $this->mockGoogleUser([
            'id' => 'google-'.Str::random(12),
            'name' => 'Google Baru',
            'email' => $email,
        ]);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('customer.home'));

        $user = User::where('email', $email)->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertSame(Role::CUSTOMER, $user->role->nama_role);
        $this->assertSame(User::STATUS_AKTIF, $user->status);
        $this->assertNotNull($user->google_id);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_login_google_intent_owner_membuat_owner_baru_langsung(): void
    {
        $email = 'google-owner-'.Str::random(8).'@example.com';
        $googleId = 'google-'.Str::random(12);

        $this->withSession(['oauth_role_intent' => 'owner']);

        $this->mockGoogleUser([
            'id' => $googleId,
            'name' => 'Google Owner',
            'email' => $email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('owner.pengajuan-toko'));

        $user = User::where('email', $email)->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertSame(Role::OWNER, $user->role->nama_role);
        $this->assertSame($googleId, $user->google_id);
        $this->assertSame(User::STATUS_AKTIF, $user->status);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_login_google_intent_customer_membuat_customer_baru(): void
    {
        $email = 'google-customer-'.Str::random(8).'@example.com';

        $this->withSession(['oauth_role_intent' => 'customer']);

        $this->mockGoogleUser([
            'id' => 'google-'.Str::random(12),
            'name' => 'Google Customer',
            'email' => $email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('customer.home'));

        $user = User::where('email', $email)->firstOrFail();

        $this->assertAuthenticatedAs($user);
        $this->assertSame(Role::CUSTOMER, $user->role->nama_role);
    }

    public function test_login_google_menghubungkan_email_yang_sudah_ada(): void
    {
        $googleId = 'google-'.Str::random(12);
        $user = $this->makeUser();

        $this->mockGoogleUser([
            'id' => $googleId,
            'email' => $user->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('customer.home'));

        $this->assertAuthenticatedAs($user);
        $this->assertSame($googleId, $user->fresh()->google_id);
    }

    public function test_login_google_dengan_google_id_terhubung_langsung_masuk(): void
    {
        $googleId = 'google-'.Str::random(12);
        $user = $this->makeUser(['google_id' => $googleId]);

        $this->mockGoogleUser([
            'id' => $googleId,
            'email' => $user->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('customer.home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_google_menolak_akun_nonaktif(): void
    {
        $user = $this->makeUser(['status' => User::STATUS_NONAKTIF]);

        $this->mockGoogleUser([
            'id' => 'google-'.Str::random(12),
            'email' => $user->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertNull($user->fresh()->google_id);
    }

    public function test_login_google_menolak_email_lokal_yang_belum_terverifikasi(): void
    {
        $user = $this->makeUser(['email_verified_at' => null]);

        $this->mockGoogleUser([
            'id' => 'google-'.Str::random(12),
            'email' => $user->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertNull($user->fresh()->google_id);
    }

    public function test_login_google_menolak_akun_soft_deleted(): void
    {
        $user = $this->makeUser();
        $user->delete();

        $this->mockGoogleUser([
            'id' => 'google-'.Str::random(12),
            'email' => $user->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertNull(User::withTrashed()->find($user->user_id)->google_id);
    }

    public function test_login_google_menolak_google_id_yang_bentrok(): void
    {
        $user = $this->makeUser(['google_id' => 'google-lama']);

        $this->mockGoogleUser([
            'id' => 'google-baru',
            'email' => $user->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertSame('google-lama', $user->fresh()->google_id);
    }

    public function test_login_google_menolak_email_google_yang_belum_terverifikasi(): void
    {
        $email = 'google-belum-verifikasi-'.Str::random(8).'@example.com';

        $this->mockGoogleUser([
            'id' => 'google-'.Str::random(12),
            'email' => $email,
            'email_verified' => false,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => $email]);
    }

    public function test_login_google_owner_tanpa_toko_diarahkan_ke_pengajuan_toko(): void
    {
        $googleId = 'google-'.Str::random(12);
        $owner = $this->makeUser([
            'role_id' => $this->roleId(Role::OWNER),
            'google_id' => $googleId,
        ]);

        $this->mockGoogleUser([
            'id' => $googleId,
            'email' => $owner->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('owner.pengajuan-toko'));

        $this->assertAuthenticatedAs($owner);
    }

    public function test_login_google_intent_owner_pada_akun_owner_lama_tetap_masuk(): void
    {
        $googleId = 'google-'.Str::random(12);
        $owner = $this->makeUser([
            'role_id' => $this->roleId(Role::OWNER),
            'google_id' => $googleId,
        ]);

        $this->withSession(['oauth_role_intent' => 'owner']);

        $this->mockGoogleUser([
            'id' => $googleId,
            'email' => $owner->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('owner.pengajuan-toko'));

        $this->assertAuthenticatedAs($owner);
    }

    public function test_login_google_intent_customer_pada_akun_customer_lama_tetap_masuk(): void
    {
        $googleId = 'google-'.Str::random(12);
        $customer = $this->makeUser(['google_id' => $googleId]);

        $this->withSession(['oauth_role_intent' => 'customer']);

        $this->mockGoogleUser([
            'id' => $googleId,
            'email' => $customer->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('customer.home'));

        $this->assertAuthenticatedAs($customer);
    }

    public function test_login_google_intent_owner_pada_akun_customer_lama_ditolak(): void
    {
        $googleId = 'google-'.Str::random(12);
        $customer = $this->makeUser(['google_id' => $googleId]);

        $this->withSession(['oauth_role_intent' => 'owner']);

        $this->mockGoogleUser([
            'id' => $googleId,
            'email' => $customer->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertSame(Role::CUSTOMER, $customer->fresh()->role->nama_role);
    }

    public function test_login_google_intent_customer_pada_akun_owner_lama_ditolak(): void
    {
        $googleId = 'google-'.Str::random(12);
        $owner = $this->makeUser([
            'role_id' => $this->roleId(Role::OWNER),
            'google_id' => $googleId,
        ]);

        $this->withSession(['oauth_role_intent' => 'customer']);

        $this->mockGoogleUser([
            'id' => $googleId,
            'email' => $owner->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertSame(Role::OWNER, $owner->fresh()->role->nama_role);
    }

    public function test_login_google_intent_tidak_mengubah_role_internal(): void
    {
        $googleId = 'google-'.Str::random(12);
        $admin = $this->makeUser([
            'role_id' => $this->roleId(Role::ADMIN),
            'google_id' => $googleId,
        ]);

        $this->withSession(['oauth_role_intent' => 'customer']);

        $this->mockGoogleUser([
            'id' => $googleId,
            'email' => $admin->email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
        $this->assertSame(Role::ADMIN, $admin->fresh()->role->nama_role);
    }

    public function test_login_google_memakai_cookie_area_saat_multi_role_aktif(): void
    {
        config(['session.multi_role' => true]);

        $googleId = 'google-'.Str::random(12);
        $admin = $this->makeUser([
            'role_id' => $this->roleId(Role::ADMIN),
            'google_id' => $googleId,
        ]);

        $this->mockGoogleUser([
            'id' => $googleId,
            'email' => $admin->email,
        ]);

        $oldSessionId = session()->getId();

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('admin.dashboard'))
            ->assertCookie('raliva_admin_session');

        $this->assertNotSame($oldSessionId, session()->getId());
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_google_mempertahankan_tujuan_redirect(): void
    {
        $email = 'google-redirect-'.Str::random(8).'@example.com';

        $this->get(route('auth.google.redirect', ['redirect' => '/customer/chart']))
            ->assertRedirect();

        $this->mockGoogleUser([
            'id' => 'google-'.Str::random(12),
            'email' => $email,
        ]);

        $this->get(route('auth.google.callback'))
            ->assertRedirect('/customer/chart');

        $this->assertAuthenticated();
    }

    public function test_callback_google_menangani_penolakan_akses(): void
    {
        $this->get(route('auth.google.callback', ['error' => 'access_denied']))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_tombol_google_hanya_muncul_di_halaman_login(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee(__('SIGN IN WITH GOOGLE'))
            ->assertSee('id="google-login-trigger"', false)
            ->assertSee('id="google-role-modal"', false)
            ->assertSee(route('auth.google.redirect', ['intent' => 'owner']), false)
            ->assertSee(route('auth.google.redirect', ['intent' => 'customer']), false)
            ->assertSee(__('Pemilik Bisnis'));

        $this->get(route('register'))
            ->assertOk()
            ->assertDontSee(__('SIGN UP WITH GOOGLE'))
            ->assertDontSee(__('Sign up with Google'));
    }

    public function test_modal_google_mempertahankan_tujuan_redirect(): void
    {
        $this->get(route('login', ['redirect' => '/customer/chart']))
            ->assertOk()
            ->assertSee(route('auth.google.redirect', ['intent' => 'owner', 'redirect' => '/customer/chart']))
            ->assertSee(route('auth.google.redirect', ['intent' => 'customer', 'redirect' => '/customer/chart']));
    }

    public function test_tombol_google_tersembunyi_saat_konfigurasi_kosong(): void
    {
        config(['services.google.client_id' => null]);

        $this->get(route('login'))
            ->assertOk()
            ->assertDontSee(__('SIGN IN WITH GOOGLE'))
            ->assertDontSee('id="google-login-trigger"', false)
            ->assertDontSee('id="google-role-modal"', false)
            ->assertDontSee(route('auth.google.redirect', ['intent' => 'owner']), false);
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

    private function makeUser(array $overrides = []): User
    {
        return User::create(array_merge([
            'nama_lengkap' => 'User Google '.Str::random(6),
            'email' => 'user-google-'.Str::random(10).'@example.com',
            'password' => 'Raliva2026!',
            'role_id' => $this->roleId(Role::CUSTOMER),
            'status' => User::STATUS_AKTIF,
            'email_verified_at' => now(),
        ], $overrides));
    }

    private function roleId(string $role): int
    {
        return (int) Role::query()->firstOrCreate(
            ['nama_role' => $role],
            ['deskripsi' => $role, 'status' => 'aktif']
        )->role_id;
    }
}
