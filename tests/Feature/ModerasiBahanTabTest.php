<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ModerasiBahanTabTest extends TestCase
{
    use DatabaseTransactions;

    public function test_moderasi_detail_has_produk_and_bahan_tabs(): void
    {
        $superAdmin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::SUPER_ADMIN))
            ->where('status', User::STATUS_AKTIF)
            ->firstOrFail();

        $response = $this->actingAs($superAdmin)->get(route('superadmin.moderasi-produk'));

        $response->assertOk();
        $response->assertSee('Informasi Produk', false);
        $response->assertSee('Informasi Bahan', false);
        $response->assertSee('data-mod-tab', false);
        $response->assertSee('mod-bahan-rows', false);
        $response->assertSee('data-bahan', false);
    }
}
