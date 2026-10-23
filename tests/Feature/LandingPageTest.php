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

    public function test_landing_utama_dapat_diakses_guest(): void
    {
        $response = $this->get(route('landing'));

        $response->assertOk()
            ->assertSee('NexPrint')
            ->assertSee(route('landing.brand'), false)
            ->assertSee(route('landing.open-store'), false)
            ->assertSee(route('customer.home'), false)
            ->assertSee('Bangun brand fashion', false);
    }

    public function test_halaman_untuk_brand_dan_buka_toko_dapat_diakses_guest(): void
    {
        $this->get(route('landing.brand'))
            ->assertOk()
            ->assertSee('Untuk brand fashion')
            ->assertSee(route('landing.open-store'), false);

        $this->get(route('landing.open-store'))
            ->assertOk()
            ->assertSee(route('register'), false)
            ->assertSee('Pertanyaan yang sering diajukan');
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
            ->assertDontSee('instagram.com', false);
    }

    public function test_halaman_buka_toko_menampilkan_cta_dan_komisi_platform(): void
    {
        Setting::set(Setting::KOMISI_PERSEN_DEFAULT, '5');

        $this->get(route('landing.open-store'))
            ->assertOk()
            ->assertSee(route('register'), false)
            ->assertSee('5%', false)
            ->assertSee('KTP wajib');
    }

    public function test_landing_mengikuti_bahasa_aktif(): void
    {
        app()->setLocale('en');

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Build your fashion brand', false)
            ->assertSee('Open a Store', false);
    }
}
