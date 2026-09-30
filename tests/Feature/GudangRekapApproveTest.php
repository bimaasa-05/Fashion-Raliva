<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\StockTransfer;
use App\Models\Store;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\KaryawanReportService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GudangRekapApproveTest extends TestCase
{
    use DatabaseTransactions;

    public function test_approved_transfer_counts_for_approver(): void
    {
        $ownerRole = Role::where('nama_role', 'Owner')->firstOrFail();
        $gudangRole = Role::where('nama_role', 'Gudang')->firstOrFail();
        $mkUser = function (string $role, string $nama) use ($ownerRole, $gudangRole) {
            return User::create([
                'role_id' => ($role === 'Owner' ? $ownerRole : $gudangRole)->role_id,
                'nama_lengkap' => $nama,
                'email' => strtolower(str_replace(' ', '-', $nama)).'-'.uniqid().'@test.local',
                'password' => bcrypt('secret123'),
                'status' => 'aktif',
            ]);
        };
        $owner = $mkUser('Owner', 'Owner GudangAppr');
        $peminta = $mkUser('Gudang', 'Gudang Minta');
        $penyetuju = $mkUser('Gudang', 'Gudang Setuju');
        $store = Store::create([
            'owner_id' => $owner->user_id,
            'nama_toko' => 'Toko GudangAppr',
            'alamat' => 'Jl. GA No. 1',
            'status' => Store::STATUS_AKTIF,
        ]);
        $mkWh = fn (string $nama) => Warehouse::create([
            'store_id' => $store->store_id,
            'nama_gudang' => $nama.' '.uniqid(),
            'alamat' => 'Jl. WH',
            'status' => Warehouse::STATUS_AKTIF,
        ]);

        StockTransfer::create([
            'from_warehouse_id' => $mkWh('Asal')->warehouse_id,
            'to_warehouse_id' => $mkWh('Tujuan')->warehouse_id,
            'requested_by' => $peminta->user_id,
            'approved_by' => $penyetuju->user_id,
            'status' => StockTransfer::STATUS_RECEIVED,
            'diminta_pada' => now()->subHours(3),
            'diterima_pada' => now(),
        ]);

        $svc = app(KaryawanReportService::class);
        $rekapMinta = $svc->rekapGudang($peminta->user_id, [$store->store_id]);
        $rekapSetuju = $svc->rekapGudang($penyetuju->user_id, [$store->store_id]);

        $this->assertSame(1, $rekapMinta['transfer_diminta']);
        $this->assertSame(0, $rekapMinta['transfer_disetujui']);
        $this->assertSame(0, $rekapSetuju['transfer_diminta']);
        $this->assertSame(1, $rekapSetuju['transfer_disetujui']);
    }
}
