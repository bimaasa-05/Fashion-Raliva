<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PengirimanModalTest extends TestCase
{
    use DatabaseTransactions;

    public function test_pengiriman_page_renders_with_modals_outside_table(): void
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::ADMIN))
            ->whereHas('storeAssignments', fn ($q) => $q->where('status', 'aktif'))
            ->firstOrFail();
        StoreStaff::where('user_id', $admin->user_id)->where('status', 'aktif')->firstOrFail();
        $this->flushSession();

        $response = $this->actingAs($admin)->get(route('admin.pengiriman'));
        $html = $response->getContent();

        $response->assertOk();
        $response->assertDontSee('return confirm(', false);
        // Modal kirim tidak boleh bersarang di dalam <td> tabel.
        $this->assertDoesNotMatchRegularExpression('/<td[^>]*>.*id="modal-kirim-/s', $html);
    }
}
