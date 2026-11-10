<?php

namespace Tests\Feature;

use App\Models\Checkout;
use App\Models\Order;
use App\Models\Role;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardSidebarRegressionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_semua_grup_sidebar_tertutup_secara_default_untuk_semua_role(): void
    {
        $owner = $this->makeUser(Role::OWNER, 'owner-sidebar');
        $store = $this->makeStore($owner);

        $superAdmin = $this->makeUser(Role::SUPER_ADMIN, 'super-sidebar');
        $admin = $this->makeUser(Role::ADMIN, 'admin-sidebar');
        $produksi = $this->makeUser(Role::PRODUKSI, 'produksi-sidebar');
        $gudang = $this->makeUser(Role::GUDANG, 'gudang-sidebar');

        $this->assignStore($admin, $store);
        $this->assignStore($produksi, $store);
        $this->assignStore($gudang, $store);

        $cases = [
            'superadmin.dashboard' => $superAdmin,
            'admin.dashboard' => $admin,
            'owner.dashboard' => $owner,
            'gudang.dashboard' => $gudang,
            'produksi.dashboard' => $produksi,
        ];

        foreach ($cases as $route => $user) {
            $this->flushSession();
            $response = $this->actingAs($user)->get(route($route));

            $this->assertSame(200, $response->getStatusCode(), "Route {$route} tidak mengembalikan 200. Redirect: ".$response->headers->get('Location'));
            $response->assertSee('data-sidebar-group-button aria-expanded="false"', false);
            $response->assertDontSee('data-sidebar-group-button aria-expanded="true"', false);

            if ($route === 'superadmin.dashboard') {
                $response->assertSee('class="sa-sidebar-group "', false);
                $response->assertDontSee('class="sa-sidebar-group sa-group-open"', false);
            } else {
                $response->assertSee('duration-300 ease-out grid-rows-[0fr]', false);
                $response->assertDontSee('duration-300 ease-out grid-rows-[1fr]', false);
            }
        }
    }

    public function test_label_sidebar_superadmin_memakai_dashboard(): void
    {
        $superAdmin = $this->makeUser(Role::SUPER_ADMIN, 'super-label');

        $response = $this->actingAs($superAdmin)->get(route('superadmin.dashboard'));

        $response->assertOk();
        $response->assertSee('title="Dashboard">Dashboard</span>', false);
        $response->assertDontSee('Dasboard', false);
    }

    public function test_gauge_superadmin_tanpa_data_tidak_menampilkan_100_persen(): void
    {
        $superAdmin = $this->makeUser(Role::SUPER_ADMIN, 'super-gauge');

        $this->withoutForeignKeyChecks(function () {
            DB::table('orders')->delete();
            DB::table('reviews')->delete();
            DB::table('complaints')->delete();
        });

        $response = $this->actingAs($superAdmin)->get(route('superadmin.dashboard'));

        $response->assertOk();
        $this->assertFalse($response->viewData('targetOmzet')['tersedia']);
        $this->assertFalse($response->viewData('kepuasan')['tersedia']);
        $this->assertFalse($response->viewData('sla')['tersedia']);

        $html = $response->getContent();
        $this->assertGreaterThanOrEqual(3, substr_count($html, 'data-donut-center-value="—"'));
        $response->assertSee('Belum Ada Data', false);
        $this->assertStringNotContainsString('data-donut-center-value="100%"', $html);
    }

    public function test_gauge_gudang_tanpa_gudang_tidak_menampilkan_100_persen(): void
    {
        $gudang = $this->makeUser(Role::GUDANG, 'gudang-gauge');

        $response = $this->actingAs($gudang)->get(route('gudang.dashboard'));

        $response->assertOk();

        $html = $response->getContent();
        $this->assertGreaterThanOrEqual(3, substr_count($html, 'data-donut-center-value="—"'));
        $response->assertSee('Belum Ada Data', false);
        $this->assertStringNotContainsString('data-donut-center-value="100%"', $html);
    }

    public function test_progress_tugas_admin_nol_tidak_dibulatkan_menjadi_empat_persen(): void
    {
        $owner = $this->makeUser(Role::OWNER, 'owner-admin-progress');
        $store = $this->makeStore($owner);
        $admin = $this->makeUser(Role::ADMIN, 'admin-progress');
        $this->assignStore($admin, $store);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('style="width: 0%"', false);
        $response->assertDontSee('style="width: 4%"', false);
    }

    public function test_owner_dengan_toko_tanpa_logo_memakai_ikon_storefront(): void
    {
        $owner = $this->makeUser(Role::OWNER, 'owner-logo');
        $this->makeStore($owner);

        $response = $this->actingAs($owner)->get(route('owner.dashboard'));

        $response->assertOk();
        $this->assertStringContainsString(
            'text-[28px] text-gold-accent/70" aria-hidden="true">storefront',
            $response->getContent()
        );
        $response->assertDontSee('images/logo.svg" alt="Logo', false);
    }

    public function test_owner_dashboard_merender_pesanan_terbaru(): void
    {
        $owner = $this->makeUser(Role::OWNER, 'owner-order');
        $store = $this->makeStore($owner);
        $customer = $this->makeUser(Role::CUSTOMER, 'customer-order');

        $checkout = Checkout::create([
            'user_id' => $customer->user_id,
            'subtotal' => 100000,
            'grand_total' => 100000,
            'status' => Checkout::STATUS_SELESAI,
        ]);

        $order = Order::create([
            'checkout_id' => $checkout->checkout_id,
            'store_id' => $store->store_id,
            'nomor_order' => 'INV-OWNER-'.uniqid(),
            'subtotal' => 100000,
            'grand_total' => 100000,
            'status' => Order::STATUS_SELESAI,
        ]);

        $response = $this->actingAs($owner)->get(route('owner.dashboard'));

        $response->assertOk();
        $response->assertSee($order->nomor_order);
    }

    private function makeUser(string $roleName, string $prefix): User
    {
        $role = Role::where('nama_role', $roleName)->firstOrFail();

        return User::create([
            'role_id' => $role->role_id,
            'nama_lengkap' => ucfirst($prefix),
            'email' => $prefix.'-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => User::STATUS_AKTIF,
            'email_verified_at' => now(),
        ]);
    }

    private function makeStore(User $owner): Store
    {
        return Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Uji '.uniqid(),
            'alamat' => 'Jl. Uji No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);
    }

    private function assignStore(User $user, Store $store): void
    {
        StoreStaff::create([
            'store_id' => $store->store_id,
            'user_id' => $user->user_id,
            'status' => 'aktif',
        ]);
    }

    private function withoutForeignKeyChecks(callable $callback): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            $callback();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
