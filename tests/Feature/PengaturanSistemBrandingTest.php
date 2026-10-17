<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Regresi unggah logo & favicon platform (Fase 6): file tersimpan di disk
 * public, path tercatat di Setting, dan unggahan melebihi batas ditolak.
 */
class PengaturanSistemBrandingTest extends TestCase
{
    use DatabaseTransactions;

    private function superAdmin(): User
    {
        return User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();
    }

    public function test_unggah_logo_dan_favicon_tersimpan_di_setting(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->superAdmin())->put(route('superadmin.pengaturan-sistem.update'), [
            'logo_platform' => UploadedFile::fake()->create('logo.png', 100, 'image/png'),
            'favicon_platform' => UploadedFile::fake()->create('favicon.ico', 10, 'image/x-icon'),
        ]);

        $response->assertRedirect();

        $logo = Setting::get(Setting::LOGO_PLATFORM);
        $favicon = Setting::get(Setting::FAVICON_PLATFORM);

        $this->assertNotNull($logo);
        $this->assertNotNull($favicon);
        $this->assertStringStartsWith('branding/', $logo);
        $this->assertStringStartsWith('branding/', $favicon);
        $this->assertTrue(Storage::disk('public')->exists($logo));
        $this->assertTrue(Storage::disk('public')->exists($favicon));

        Storage::disk('public')->deleteDirectory('branding');
    }

    public function test_unggah_logo_melebihi_2mb_ditolak(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->superAdmin())->put(route('superadmin.pengaturan-sistem.update'), [
            'logo_platform' => UploadedFile::fake()->create('logo.png', 3000, 'image/png'),
        ]);

        $response->assertSessionHasErrors('logo_platform');
    }

    public function test_unggah_favicon_selain_ico_png_ditolak(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->superAdmin())->put(route('superadmin.pengaturan-sistem.update'), [
            'favicon_platform' => UploadedFile::fake()->create('favicon.jpg', 10, 'image/jpeg'),
        ]);

        $response->assertSessionHasErrors('favicon_platform');
    }

    public function test_wordmark_tampilkan_logo_di_kiri_teks_nama(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('branding/logo-uji.png', 'x');
        Setting::set(Setting::LOGO_PLATFORM, 'branding/logo-uji.png');

        $html = view('partials.brand-content', ['logoClass' => 'h-7'])->render();

        $this->assertStringContainsString('<img', $html);
        $this->assertStringContainsString(e(brand()), $html);
    }

    public function test_wordmark_tanpa_logo_hanya_teks_nama(): void
    {
        Storage::fake('public');
        Setting::query()->where('kunci', 'logo_platform')->delete();

        $html = view('partials.brand-content', ['logoClass' => 'h-7'])->render();

        $this->assertStringNotContainsString('<img', $html);
        $this->assertStringContainsString(e(brand()), $html);
    }

    public function test_superadmin_dapat_menghapus_logo_platform(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('branding/logo-hapus.png', 'x');
        Setting::set(Setting::LOGO_PLATFORM, 'branding/logo-hapus.png');

        $response = $this->actingAs($this->superAdmin())->delete(route('superadmin.pengaturan-sistem.logo.destroy'));

        $response->assertRedirect();
        $response->assertSessionHas('toast');

        $this->assertSame('', (string) Setting::get(Setting::LOGO_PLATFORM));
        $this->assertSame('', brand_logo_url());
        $this->assertFalse(Storage::disk('public')->exists('branding/logo-hapus.png'));

        // Wordmark kembali ke teks saja, siap terpusat lagi seperti sebelum ada logo.
        $html = view('partials.brand-content')->render();
        $this->assertStringNotContainsString('<img', $html);
        $this->assertStringContainsString(e(brand()), $html);

        Storage::disk('public')->deleteDirectory('branding');
    }

    public function test_hapus_logo_saat_belum_ada_logo_tetap_aman(): void
    {
        Storage::fake('public');
        Setting::query()->where('kunci', Setting::LOGO_PLATFORM)->delete();

        $response = $this->actingAs($this->superAdmin())->delete(route('superadmin.pengaturan-sistem.logo.destroy'));

        $response->assertRedirect();
        $response->assertSessionHas('toast');
    }

    public function test_tombol_dan_form_hapus_logo_tampil_di_halaman_pengaturan(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('branding/logo-uji.png', 'x');
        Setting::set(Setting::LOGO_PLATFORM, 'branding/logo-uji.png');

        $response = $this->actingAs($this->superAdmin())->get(route('superadmin.pengaturan-sistem'));

        $response->assertOk();
        $response->assertSee('modal-logo-hapus', false);
        $response->assertSee(route('superadmin.pengaturan-sistem.logo.destroy'), false);

        Storage::disk('public')->deleteDirectory('branding');
    }
}
