<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Regresi helper brand(): nama platform yang tampil di seluruh UI.
 * Sumber: Setting "nama_platform" (Super Admin), fallback config('app.name').
 */
class BrandNameTest extends TestCase
{
    use DatabaseTransactions;

    public function test_brand_uses_nama_platform_setting(): void
    {
        Setting::set(Setting::NAMA_PLATFORM, 'Uji Brand');

        $this->assertSame('Uji Brand', brand());
    }

    public function test_brand_falls_back_to_app_name_when_setting_missing(): void
    {
        Setting::query()->where('kunci', Setting::NAMA_PLATFORM)->delete();
        config(['app.name' => 'Nama Fallback']);

        $this->assertSame('Nama Fallback', brand());
    }

    public function test_brand_trims_and_falls_back_when_setting_blank(): void
    {
        Setting::set(Setting::NAMA_PLATFORM, '   ');
        config(['app.name' => 'Cadangan']);

        $this->assertSame('Cadangan', brand());
    }

    public function test_favicon_uses_favicon_platform_setting(): void
    {
        Setting::set('favicon_platform', 'branding/uji-favicon.png');

        $this->assertSame(photo_url('branding/uji-favicon.png'), brand_favicon_url());
    }

    public function test_favicon_falls_back_to_default_icon_when_setting_missing(): void
    {
        Setting::query()->where('kunci', 'favicon_platform')->delete();

        $this->assertSame(asset('favicon.ico'), brand_favicon_url());
    }
}
