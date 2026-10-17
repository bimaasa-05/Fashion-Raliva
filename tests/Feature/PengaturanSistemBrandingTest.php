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
}
