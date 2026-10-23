<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LocaleSwitcherTest extends TestCase
{
    use DatabaseTransactions;

    public function test_switch_locale_menyimpan_pilihan_di_session(): void
    {
        $response = $this->from(route('login'))
            ->post(route('customer.locale.switch'), ['locale' => 'en']);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('locale', 'en');

        $response = $this->from(route('login'))
            ->post(route('customer.locale.switch'), ['locale' => 'id']);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('locale', 'id');
    }

    public function test_locale_tidak_valid_ditolak(): void
    {
        $response = $this->from(route('login'))
            ->post(route('customer.locale.switch'), ['locale' => 'fr']);

        $response->assertSessionHasErrors('locale');
    }

    public function test_topbar_superadmin_memuat_pemilih_bahasa(): void
    {
        $superAdmin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();

        $this->actingAs($superAdmin)
            ->get(route('superadmin.dashboard'))
            ->assertOk()
            ->assertSee(route('customer.locale.switch'), false)
            ->assertSee('value="id"', false)
            ->assertSee('value="en"', false);
    }

    public function test_semua_layout_role_memuat_partial_pemilih_bahasa(): void
    {
        foreach (['superadmin', 'owner', 'admin', 'produksi', 'gudang'] as $layout) {
            $contents = file_get_contents(resource_path("views/layouts/{$layout}.blade.php"));

            $this->assertStringContainsString(
                "partials.locale-switcher",
                $contents,
                "Layout {$layout} belum memuat pemilih bahasa."
            );
        }
    }
}
