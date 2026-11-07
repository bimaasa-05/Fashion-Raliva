<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthShadowTest extends TestCase
{
    public function test_register_dan_lupa_password_memakai_auth_card_tanpa_shadow_light_mode(): void
    {
        foreach (['register', 'forgot-password'] as $view) {
            $contents = file_get_contents(resource_path("views/customer/auth/{$view}.blade.php"));

            $this->assertStringContainsString('auth-card', $contents);
            $this->assertStringContainsString('html:not(.theme-dark) .auth-card', $contents);
            $this->assertStringContainsString('box-shadow: none;', $contents);
        }
    }

    public function test_halaman_register_dan_lupa_password_dapat_diakses(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('auth-card', false);

        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('auth-card', false);
    }

    public function test_judul_tab_login_memakai_nama_brand_kapital(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('<title>NEXPRINT - ', false);
    }
}
