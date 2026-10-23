<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Regresi tema toast & popup notifikasi: di dark mode tidak boleh memakai
 * background putih/inverse-surface yang membuat komponen tampil terang.
 */
class ToastThemeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_toast_flash_punya_override_tema_dark(): void
    {
        $css = file_get_contents(resource_path('views/partials/theme-head.blade.php'));

        $this->assertStringContainsString('.dark #raliva-toast', $css);
        $this->assertStringContainsString('--c-sc-highest', $css);
        $this->assertStringContainsString('--c-on-surface', $css);
        $this->assertStringContainsString('--c-outline-variant', $css);
    }

    public function test_popup_notifikasi_memakai_surface_tema(): void
    {
        $popup = file_get_contents(resource_path('views/partials/notification-popup.blade.php'));

        $this->assertStringNotContainsString('bg-white', $popup);
        $this->assertStringContainsString('bg-surface-container-lowest', $popup);
    }

    public function test_semua_sumber_toast_memakai_id_yang_sama(): void
    {
        $sources = [
            resource_path('views/partials/ui-scripts.blade.php'),
            resource_path('views/layouts/owner.blade.php'),
            resource_path('views/layouts/produksi.blade.php'),
        ];

        foreach ($sources as $source) {
            $this->assertStringContainsString(
                'id="raliva-toast"',
                file_get_contents($source),
                basename($source) . ' tidak memuat toast.'
            );
        }
    }

    public function test_dashboard_superadmin_memuat_toast(): void
    {
        $superAdmin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();

        $this->actingAs($superAdmin)
            ->get(route('superadmin.dashboard'))
            ->assertOk()
            ->assertSee('id="raliva-toast"', false);
    }
}
