<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminLaporanExportTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Assertion memakai string Indonesia; kunci locale agar deterministik meski .env memakai en.
        app()->setLocale('id');
    }

    /**
     * @return array{0: User, 1: Store}
     */
    private function adminDenganToko(): array
    {
        $ownerRole = Role::where('nama_role', 'Owner')->firstOrFail();
        $owner = User::create([
            'role_id' => $ownerRole->role_id,
            'nama_lengkap' => 'Owner Export',
            'email' => 'owner-export-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko Export',
            'alamat' => 'Jl. Export No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);

        $admin = $this->adminTanpaToko();
        StoreStaff::create([
            'store_id' => $store->store_id,
            'user_id' => $admin->user_id,
            'status' => 'aktif',
        ]);

        return [$admin, $store];
    }

    private function adminTanpaToko(): User
    {
        $role = Role::where('nama_role', 'Admin')->firstOrFail();

        return User::create([
            'role_id' => $role->role_id,
            'nama_lengkap' => 'Admin Export '.Str::random(5),
            'email' => 'admin-export-'.uniqid().'@test.local',
            'password' => bcrypt('secret123'),
            'status' => 'aktif',
        ]);
    }

    public function test_export_excel_laporan_admin(): void
    {
        [$admin] = $this->adminDenganToko();

        $dari = now()->subDays(6)->toDateString();
        $sampai = now()->toDateString();

        $res = $this->actingAs($admin)->get(route('admin.laporan.export-excel', [
            'dari' => $dari,
            'sampai' => $sampai,
        ]));

        // Generasi nyata (tanpa fake): memastikan seluruh sheet tereksekusi tanpa error.
        $res->assertOk();
        $res->assertDownload('laporan-operasional-'.$dari.'-'.$sampai.'.xlsx');
    }

    public function test_cetak_dan_pdf_laporan_admin(): void
    {
        [$admin] = $this->adminDenganToko();

        $cetak = $this->actingAs($admin)->get(route('admin.laporan.cetak'));
        $cetak->assertOk();
        $cetak->assertSee('Laporan Operasional', false);
        $cetak->assertSee('Rincian Harian', false);

        $pdf = $this->actingAs($admin)->get(route('admin.laporan.export-pdf'));
        $pdf->assertOk();
        $pdf->assertHeader('content-type', 'application/pdf');
    }

    public function test_export_tanpa_toko_tidak_bisa_diunduh(): void
    {
        $admin = $this->adminTanpaToko();

        $res = $this->actingAs($admin)->get(route('admin.laporan.export-excel'));

        $res->assertRedirect();
        $res->assertSessionHas('toast');
    }
}
