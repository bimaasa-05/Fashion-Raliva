<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class LandingOfferSettingsTest extends TestCase
{
    use DatabaseTransactions;

    private const OFFER_FIELDS = [
        'eyebrow',
        'title',
        'description',
        'benefit_1',
        'benefit_2',
        'benefit_3',
        'requirements_title',
        'requirement_1',
        'requirement_2',
        'requirement_3',
        'requirement_note',
        'cta_primary',
        'cta_secondary',
        'commission_note',
    ];

    private function superAdmin(): User
    {
        return User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
    }

    private function payload(array $overrides = []): array
    {
        $payload = [];

        foreach (self::OFFER_FIELDS as $field) {
            $payload["landing_offer_{$field}_id"] = "ID {$field}";
            $payload["landing_offer_{$field}_en"] = "EN {$field}";
        }

        return array_merge($payload, $overrides);
    }

    public function test_superadmin_dapat_menyimpan_konten_penawaran_landing(): void
    {
        $response = $this->actingAs($this->superAdmin())
            ->put(route('superadmin.pengaturan-sistem.landing-penawaran.update'), $this->payload([
                'landing_offer_title_id' => 'Judul dari Superadmin',
                'landing_offer_title_en' => 'Title from Superadmin',
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('toast');

        $this->assertDatabaseHas('settings', [
            'kunci' => 'landing_offer_title_id',
            'nilai' => 'Judul dari Superadmin',
        ]);
        $this->assertDatabaseHas('settings', [
            'kunci' => 'landing_offer_title_en',
            'nilai' => 'Title from Superadmin',
        ]);
    }

    public function test_landing_menampilkan_konten_penawaran_dari_setting(): void
    {
        Setting::set('landing_offer_eyebrow_id', 'Eyebrow Kustom');
        Setting::set('landing_offer_title_id', 'Judul Kustom');
        Setting::set('landing_offer_description_id', 'Deskripsi Kustom');
        Setting::set('landing_offer_benefit_1_id', 'Benefit Kustom');
        Setting::set('landing_offer_requirements_title_id', 'Kebutuhan Kustom');
        Setting::set('landing_offer_requirement_1_id', 'Syarat Kustom');
        Setting::set('landing_offer_requirement_note_id', 'Catatan Kustom');
        Setting::set('landing_offer_cta_primary_id', 'CTA Kustom');
        Setting::set('landing_offer_cta_secondary_id', 'CTA Kedua Kustom');
        Setting::set('landing_offer_commission_note_id', 'Komisi {percent}% dan :percent%');
        Setting::set(Setting::KOMISI_PERSEN_DEFAULT, '7.5');

        app()->setLocale('id');

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Eyebrow Kustom')
            ->assertSee('Judul Kustom')
            ->assertSee('Deskripsi Kustom')
            ->assertSee('Benefit Kustom')
            ->assertSee('Kebutuhan Kustom')
            ->assertSee('Syarat Kustom')
            ->assertSee('Catatan Kustom')
            ->assertSee('CTA Kustom')
            ->assertSee('CTA Kedua Kustom')
            ->assertSee('Komisi 7.5% dan 7.5%', false);
    }

    public function test_landing_memakai_fallback_saat_setting_penawaran_kosong(): void
    {
        Setting::where('kunci', 'like', 'landing_offer_%')->delete();

        app()->setLocale('id');

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee(__('landing.open_store.title'))
            ->assertSee(__('landing.open_store.requirement_1'))
            ->assertSee(__('landing.open_store.cta_primary'));
    }

    public function test_locale_en_memakai_konten_en_dan_fallback_ke_id(): void
    {
        Setting::where('kunci', 'like', 'landing_offer_%')->delete();
        Setting::set('landing_offer_title_id', 'Judul ID Fallback');
        Setting::set('landing_offer_title_en', 'English Custom Title');

        app()->setLocale('en');

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('English Custom Title');

        Setting::set('landing_offer_title_en', '');

        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Judul ID Fallback');
    }

    public function test_form_penawaran_landing_terisi_konten_yang_tampil(): void
    {
        Setting::where('kunci', 'like', 'landing_offer_%')->delete();

        $this->actingAs($this->superAdmin())
            ->get(route('superadmin.pengaturan-sistem'))
            ->assertOk()
            ->assertSee('value="'.e(__('landing.open_store.title', [], 'id')).'"', false)
            ->assertSee('value="'.e(__('landing.open_store.title', [], 'en')).'"', false)
            ->assertSee('>'.e(__('landing.open_store.description', [], 'id')).'</textarea>', false);
    }

    public function test_form_penawaran_landing_menampilkan_setting_tersimpan(): void
    {
        Setting::set('landing_offer_title_id', 'Judul Tersimpan');
        Setting::set('landing_offer_title_en', 'Saved Title');

        $this->actingAs($this->superAdmin())
            ->get(route('superadmin.pengaturan-sistem'))
            ->assertOk()
            ->assertSee('value="Judul Tersimpan"', false)
            ->assertSee('value="Saved Title"', false);
    }

    public function test_form_penawaran_landing_en_mengikuti_id_saat_en_kosong(): void
    {
        Setting::set('landing_offer_title_id', 'Judul ID Saja');
        Setting::set('landing_offer_title_en', '');

        $this->actingAs($this->superAdmin())
            ->get(route('superadmin.pengaturan-sistem'))
            ->assertOk()
            ->assertSee('value="Judul ID Saja"', false);
    }

    public function test_validasi_menolak_judul_dan_deskripsi_id_kosong(): void
    {
        $response = $this->actingAs($this->superAdmin())
            ->put(route('superadmin.pengaturan-sistem.landing-penawaran.update'), $this->payload([
                'landing_offer_title_id' => '',
                'landing_offer_description_id' => '',
            ]));

        $response->assertSessionHasErrors([
            'landing_offer_title_id',
            'landing_offer_description_id',
        ]);
    }

    public function test_user_selain_superadmin_tidak_bisa_mengubah_penawaran_landing(): void
    {
        $customer = User::create([
            'role_id' => Role::where('nama_role', Role::CUSTOMER)->value('role_id'),
            'nama_lengkap' => 'Customer Offer '.Str::random(6),
            'email' => 'customer-offer-'.Str::random(8).'@test.local',
            'password' => bcrypt('secret123'),
            'status' => User::STATUS_AKTIF,
        ]);

        $this->actingAs($customer)
            ->put(route('superadmin.pengaturan-sistem.landing-penawaran.update'), $this->payload())
            ->assertForbidden();
    }

    public function test_halaman_pengaturan_menampilkan_section_penawaran_landing(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('superadmin.pengaturan-sistem'))
            ->assertOk()
            ->assertSee('kartu-landing-penawaran', false)
            ->assertSee('Penawaran Landing');
    }
}
