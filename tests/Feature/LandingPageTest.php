<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        app()->setLocale('id');
        Setting::set(Setting::NAMA_PLATFORM, 'NexPrint');
    }

    public function test_landing_satu_halaman_dapat_diakses_guest(): void
    {
        $response = $this->get(route('landing'));

        $response->assertOk()
            ->assertSee('NexPrint')
            ->assertSee('Satu platform', false)
            ->assertSee('id="beranda"', false)
            ->assertSee('id="fitur"', false)
            ->assertSee('id="cara-kerja"', false)
            ->assertSee('id="untuk-siapa"', false)
            ->assertSee('id="daftar"', false)
            ->assertSee('id="faq"', false)
            ->assertDontSee('id="tentang"', false)
            ->assertDontSee('id="buka-toko"', false)
            ->assertDontSee('id="kontak"', false)
            ->assertSee(route('customer.home'), false)
            ->assertSee(route('register'), false)
            ->assertSee('Belanja');
    }

    public function test_url_lama_diarahkan_ke_anchor_landing(): void
    {
        $brand = $this->get('/untuk-brand');
        $brand->assertStatus(301);
        $this->assertSame('/#untuk-siapa', $brand->headers->get('Location'));

        $openStore = $this->get('/buka-toko');
        $openStore->assertStatus(301);
        $this->assertSame('/#daftar', $openStore->headers->get('Location'));
    }

    public function test_kontak_support_muncul_saat_setting_terisi(): void
    {
        Setting::set(Setting::EMAIL_SUPPORT, 'halo@nexprint.test');
        Setting::set(Setting::WHATSAPP_SUPPORT, '6281234567890');
        Setting::set(Setting::INSTAGRAM_SUPPORT, '@nexprint');

        $response = $this->get(route('landing'));

        $response->assertOk()
            ->assertSee('halo@nexprint.test')
            ->assertSee('https://wa.me/6281234567890', false)
            ->assertSee('https://instagram.com/nexprint', false);
    }

    public function test_kontak_support_disembunyikan_saat_setting_kosong(): void
    {
        Setting::set(Setting::EMAIL_SUPPORT, '');
        Setting::set(Setting::WHATSAPP_SUPPORT, '');
        Setting::set(Setting::INSTAGRAM_SUPPORT, '');

        $response = $this->get(route('landing'));

        $response->assertOk()
            ->assertDontSee('mailto:', false)
            ->assertDontSee('wa.me', false)
            ->assertDontSee('instagram.com', false)
            ->assertSee('Kanal kontak belum diatur');
    }

    public function test_landing_menampilkan_cta_daftar_dan_komisi_platform(): void
    {
        Setting::set(Setting::KOMISI_PERSEN_DEFAULT, '5');

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee(route('register'), false)
            ->assertSee('Daftar Owner')
            ->assertSee('5%', false)
            ->assertSee('KTP wajib');
    }

    public function test_landing_mengikuti_bahasa_aktif(): void
    {
        app()->setLocale('en');

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('One platform for', false)
            ->assertSee('How it works', false)
            ->assertSee('Join as Owner', false)
            ->assertSee('Shop');
    }
}
