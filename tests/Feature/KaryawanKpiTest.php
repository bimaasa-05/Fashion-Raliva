<?php

namespace Tests\Feature;

use App\Http\Controllers\Owner\RekapKaryawanController;
use App\Models\Role;
use App\Services\KaryawanReportService;
use App\Models\StoreStaff;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KaryawanKpiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_role_key_uses_role_names_not_ids(): void
    {
        $this->assertSame('admin', RekapKaryawanController::roleKey(999, Role::ADMIN));
        $this->assertSame('produksi', RekapKaryawanController::roleKey(999, Role::PRODUKSI));
        $this->assertSame('gudang', RekapKaryawanController::roleKey(999, Role::GUDANG));
        $this->assertSame('lainnya', RekapKaryawanController::roleKey(999, 'Owner'));
        $this->assertSame('lainnya', RekapKaryawanController::roleKey(null, null));
    }

    public function test_rentang_periode_parsing(): void
    {
        $report = new KaryawanReportService();

        $this->assertNull($report->rentangPeriode(null, null));
        $this->assertNull($report->rentangPeriode('', ''));
        $range = $report->rentangPeriode('2026-01-01', '2026-01-31');
        $this->assertIsArray($range);
        $this->assertSame('2026-01-01', $range[0]->toDateString());
        $this->assertSame('2026-01-31', $range[1]->toDateString());
        $this->assertNull($report->rentangPeriode('bukan-tanggal', null));
    }

    public function test_rekap_admin_returns_bounded_metrics(): void
    {
        [$userId, $storeIds] = $this->staffFixture();
        $report = new KaryawanReportService();

        $r = $report->rekapAdmin($userId, $storeIds);

        foreach (['diverifikasi', 'ditolak', 'pesanan', 'rating_count'] as $k) {
            $this->assertGreaterThanOrEqual(0, $r[$k]);
        }
        $this->assertGreaterThanOrEqual(0, $r['pendapatan']);
        $this->assertTrue($r['cr'] === null || ($r['cr'] >= 0 && $r['cr'] <= 100));
        $this->assertTrue($r['aov'] === null || $r['aov'] >= 0);
        $this->assertTrue($r['rating'] === null || ($r['rating'] >= 0 && $r['rating'] <= 5));
    }

    public function test_rekap_produksi_returns_bounded_metrics(): void
    {
        [$userId, $storeIds] = $this->staffFixture();
        $report = new KaryawanReportService();

        $r = $report->rekapProduksi($userId, $storeIds);

        $this->assertGreaterThanOrEqual(0, $r['ditugaskan']);
        $this->assertGreaterThanOrEqual(0, $r['selesai']);
        $this->assertLessThanOrEqual($r['ditugaskan'], $r['selesai'] + $r['ditugaskan']);
        $this->assertTrue($r['sukses_persen'] === null || ($r['sukses_persen'] >= 0 && $r['sukses_persen'] <= 100));
        $this->assertTrue($r['rata_durasi_jam'] === null || $r['rata_durasi_jam'] >= 0);
    }

    public function test_rekap_gudang_returns_bounded_metrics(): void
    {
        [$userId, $storeIds] = $this->staffFixture();
        $report = new KaryawanReportService();

        $r = $report->rekapGudang($userId, $storeIds);

        foreach (['transfer_diminta', 'transfer_selesai', 'transfer_batal', 'mutasi', 'opname', 'kerusakan', 'kerusakan_qty'] as $k) {
            $this->assertGreaterThanOrEqual(0, $r[$k]);
        }
        $this->assertTrue($r['akurasi_persen'] === null || ($r['akurasi_persen'] >= 0 && $r['akurasi_persen'] <= 100));
        $this->assertTrue($r['rata_putaran_jam'] === null || $r['rata_putaran_jam'] >= 0);
    }

    public function test_ringkasan_keuangan_returns_roi_ltv(): void
    {
        [, $storeIds] = $this->staffFixture();
        $report = new KaryawanReportService();

        $r = $report->ringkasanKeuangan($storeIds);

        foreach (['revenue', 'refunds', 'expenses', 'ads', 'investasi', 'customers'] as $k) {
            $this->assertGreaterThanOrEqual(0, $r[$k]);
        }
        $this->assertEquals($r['revenue'] - $r['refunds'] - $r['expenses'] - $r['ads'], $r['bersih']);
        $this->assertTrue($r['roi'] === null || is_numeric($r['roi']));
        $this->assertTrue($r['ltv'] === null || $r['ltv'] >= 0);
    }

    public function test_period_filter_narrows_results(): void
    {
        [$userId, $storeIds] = $this->staffFixture();
        $report = new KaryawanReportService();

        $penuh = $report->rekapAdmin($userId, $storeIds);
        $tua = $report->rekapAdmin($userId, $storeIds, $report->rentangPeriode('2000-01-01', '2000-12-31'));

        $this->assertSame(0, $tua['diverifikasi'] + $tua['ditolak']);
        $this->assertSame(0, $tua['pesanan']);
        $this->assertLessThanOrEqual($penuh['diverifikasi'] + $penuh['ditolak'], $tua['diverifikasi'] + $tua['ditolak']);
        $this->assertLessThanOrEqual($penuh['pesanan'], $tua['pesanan']);
    }

    public function test_rekap_pages_render_for_every_role_filter(): void
    {
        $owner = User::whereHas('role', fn ($q) => $q->where('nama_role', Role::OWNER))->firstOrFail();
        $this->flushSession();

        foreach (['admin', 'produksi', 'gudang'] as $role) {
            $response = $this->actingAs($owner)->get(route('owner.rekap-karyawan', ['role' => $role]));
            $response->assertOk();
        }

        $default = $this->actingAs($owner)->get(route('owner.rekap-karyawan'));
        $default->assertOk();
        $default->assertSee('<option value="admin" selected', false);
    }

    public function test_semua_rows_carry_finance_keys_for_every_role(): void
    {
        $staff = StoreStaff::with(['user', 'user.role'])->whereNotNull('user_id')->get();
        $this->assertNotEmpty($staff, 'Butuh data staff untuk test ini.');

        $controller = new RekapKaryawanController(new KaryawanReportService());
        $method = new \ReflectionMethod($controller, 'bangunRekap');
        $method->setAccessible(true);

        foreach (['admin', 'produksi', 'gudang'] as $filter) {
            $rows = $method->invoke($controller, $staff, [(int) $staff->first()->store_id], $filter, null);
            foreach ($rows as $r) {
                foreach (['pesanan', 'pendapatan', 'pengeluaran', 'bersih'] as $key) {
                    $this->assertArrayHasKey($key, $r, "Filter {$filter}: baris {$r['nama']} kehilangan key {$key}.");
                }
            }
        }
    }

    /**
     * @return array{0: int, 1: int[]}
     */
    private function staffFixture(): array
    {
        $staff = StoreStaff::with('user')->where('status', 'aktif')->whereNotNull('user_id')->firstOrFail();

        return [(int) $staff->user_id, [(int) $staff->store_id]];
    }
}
