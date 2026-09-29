<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SessionExpiryTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_shows_expiry_notice(): void
    {
        $biasa = $this->get('/login');
        $biasa->assertOk();
        $biasa->assertDontSee('Sesi Anda telah berakhir', false);

        $expired = $this->get('/login?expired=1');
        $expired->assertOk();
        $expired->assertSee('Sesi Anda telah berakhir', false);
    }

    public function test_area_layout_includes_session_guard(): void
    {
        $owner = User::whereHas('role', fn ($q) => $q->where('nama_role', 'Owner'))->firstOrFail();
        $res = $this->actingAs($owner)->get(route('owner.dashboard'));
        $res->assertOk();
        $res->assertSee('__ralivaSessionGuard', false);
    }

    public function test_419_view_renders_friendly_page(): void
    {
        $html = view('errors.419')->render();
        $this->assertStringContainsString('Sesi Anda Telah Berakhir', $html);
        $this->assertStringContainsString('MASUK KEMBALI', $html);
    }
}
