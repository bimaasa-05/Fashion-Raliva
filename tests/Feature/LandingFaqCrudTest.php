<?php

namespace Tests\Feature;

use App\Models\LandingFaq;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LandingFaqCrudTest extends TestCase
{
    use DatabaseTransactions;

    private function superAdmin(): User
    {
        return User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
    }

    private function faq(array $attributes = []): LandingFaq
    {
        return LandingFaq::create(array_merge([
            'pertanyaan_id' => 'Pertanyaan uji?',
            'jawaban_id' => 'Jawaban uji.',
            'pertanyaan_en' => 'Test question?',
            'jawaban_en' => 'Test answer.',
            'urutan' => 1,
            'is_active' => true,
        ], $attributes));
    }

    public function test_landing_menampilkan_faq_aktif_dari_database_sesuai_urutan(): void
    {
        LandingFaq::query()->delete();

        $this->faq([
            'pertanyaan_id' => 'Pertanyaan kedua',
            'jawaban_id' => 'Jawaban kedua',
            'pertanyaan_en' => 'Second question',
            'jawaban_en' => 'Second answer',
            'urutan' => 2,
        ]);
        $this->faq([
            'pertanyaan_id' => 'Pertanyaan pertama',
            'jawaban_id' => 'Jawaban pertama',
            'pertanyaan_en' => 'First question',
            'jawaban_en' => 'First answer',
            'urutan' => 1,
        ]);
        $this->faq([
            'pertanyaan_id' => 'Pertanyaan nonaktif',
            'jawaban_id' => 'Jawaban nonaktif',
            'pertanyaan_en' => 'Inactive question',
            'jawaban_en' => 'Inactive answer',
            'urutan' => 3,
            'is_active' => false,
        ]);

        app()->setLocale('id');
        $this->get(route('landing'))
            ->assertOk()
            ->assertSeeInOrder(['Pertanyaan pertama', 'Pertanyaan kedua'])
            ->assertDontSee('Pertanyaan nonaktif');

        app()->setLocale('en');
        $this->get(route('landing'))
            ->assertOk()
            ->assertSeeInOrder(['First question', 'Second question'])
            ->assertDontSee('Inactive question');
    }

    public function test_placeholder_percent_diganti_dengan_komisi_platform(): void
    {
        LandingFaq::query()->delete();
        Setting::set(Setting::KOMISI_PERSEN_DEFAULT, '7.5');

        $this->faq([
            'pertanyaan_id' => 'Berapa komisi?',
            'jawaban_id' => 'Komisi platform {percent}% dari penjualan.',
            'pertanyaan_en' => 'What is the commission?',
            'jawaban_en' => 'The platform commission is {percent}% of sales.',
        ]);

        app()->setLocale('id');
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('7.5%', false);

        app()->setLocale('en');
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('7.5%', false);
    }

    public function test_landing_memakai_fallback_saat_tabel_faq_kosong(): void
    {
        LandingFaq::query()->delete();

        app()->setLocale('id');
        $this->get(route('landing'))
            ->assertOk()
            ->assertSee('Apakah ada biaya pendaftaran?')
            ->assertSee('KTP wajib');
    }

    public function test_superadmin_dapat_menambah_faq_landing(): void
    {
        $response = $this->actingAs($this->superAdmin())
            ->post(route('superadmin.pengaturan-sistem.landing-faq.store'), [
                'pertanyaan_id' => 'Pertanyaan baru?',
                'jawaban_id' => 'Jawaban baru.',
                'pertanyaan_en' => 'New question?',
                'jawaban_en' => 'New answer.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('toast');
        $this->assertDatabaseHas('landing_faqs', [
            'pertanyaan_id' => 'Pertanyaan baru?',
            'pertanyaan_en' => 'New question?',
            'is_active' => true,
        ]);
    }

    public function test_superadmin_dapat_memperbarui_dan_menonaktifkan_faq_landing(): void
    {
        $faq = $this->faq();

        $response = $this->actingAs($this->superAdmin())
            ->put(route('superadmin.pengaturan-sistem.landing-faq.update', $faq), [
                'pertanyaan_id' => 'Pertanyaan diubah?',
                'jawaban_id' => 'Jawaban diubah.',
                'pertanyaan_en' => 'Changed question?',
                'jawaban_en' => 'Changed answer.',
                'urutan' => 9,
                'is_active' => '0',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('toast');
        $this->assertDatabaseHas('landing_faqs', [
            'landing_faq_id' => $faq->landing_faq_id,
            'pertanyaan_id' => 'Pertanyaan diubah?',
            'urutan' => 9,
            'is_active' => false,
        ]);
    }

    public function test_superadmin_dapat_menghapus_faq_landing(): void
    {
        $faq = $this->faq();

        $response = $this->actingAs($this->superAdmin())
            ->delete(route('superadmin.pengaturan-sistem.landing-faq.destroy', $faq));

        $response->assertRedirect();
        $response->assertSessionHas('toast');
        $this->assertDatabaseMissing('landing_faqs', ['landing_faq_id' => $faq->landing_faq_id]);
    }

    public function test_validasi_faq_landing_menolak_data_tidak_lengkap(): void
    {
        $response = $this->actingAs($this->superAdmin())
            ->post(route('superadmin.pengaturan-sistem.landing-faq.store'), [
                'pertanyaan_id' => '',
                'jawaban_id' => '',
                'pertanyaan_en' => '',
                'jawaban_en' => '',
            ]);

        $response->assertSessionHasErrors([
            'pertanyaan_id',
            'jawaban_id',
            'pertanyaan_en',
            'jawaban_en',
        ]);
    }

    public function test_halaman_pengaturan_menampilkan_section_faq_landing(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('superadmin.pengaturan-sistem'))
            ->assertOk()
            ->assertSee('kartu-landing-faq', false)
            ->assertSee('FAQ Landing');
    }
}
