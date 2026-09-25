<?php

namespace App\Http\Controllers\Owner;

use App\Exports\OwnerRekapKaryawanExport;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Services\KaryawanReportService;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class RekapKaryawanController extends Controller
{
    public const ROLE_FILTERS = ['admin', 'produksi', 'gudang'];

    public function __construct(
        protected KaryawanReportService $report,
    ) {
    }

    public static function roleKey(?int $roleId, ?string $namaRole): string
    {
        return match ($namaRole) {
            Role::ADMIN => 'admin',
            Role::PRODUKSI => 'produksi',
            Role::GUDANG => 'gudang',
            default => 'lainnya',
        };
    }

    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();
        $storeIds = $storeId ? [$storeId] : [];

        $roleFilter = $request->query('role', 'admin');
        if (! in_array($roleFilter, self::ROLE_FILTERS, true)) {
            $roleFilter = 'admin';
        }
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');
        $range = $this->report->rentangPeriode(
            is_string($dari) && $dari !== '' ? $dari : null,
            is_string($sampai) && $sampai !== '' ? $sampai : null
        );

        $staff = $storeId
            ? StoreStaff::with(['user', 'user.role'])->where('store_id', $storeId)->get()
            : collect();

        $rows = $this->bangunRekap($staff, $storeIds, $roleFilter, $range);
        $totals = $this->hitungTotal($rows, $roleFilter);
        $ringkasan = $storeIds ? $this->report->ringkasanKeuangan($storeIds, $range) : null;

        $storeName = OwnerContext::currentStore()?->nama_toko ?? 'Toko Saya';

        return view('Owner.rekap-karyawan.index', compact(
            'rows', 'totals', 'ringkasan', 'roleFilter', 'storeName', 'staff', 'dari', 'sampai'
        ));
    }

    public function exportExcel(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();
        if (! $storeId) {
            return back()->with('toast', ['message' => 'Belum ada toko untuk diekspor.', 'icon' => 'storefront']);
        }

        [$rows, $roleFilter, $dari, $sampai] = $this->siapkanExport($request, $storeId);
        $fileName = 'rekap-karyawan-' . $roleFilter . '-' . now()->translatedFormat('Y-m-d') . '.xlsx';

        return Excel::download(
            new OwnerRekapKaryawanExport($rows->all(), OwnerContext::currentStore()?->nama_toko ?? 'Toko Saya', $roleFilter, $this->hitungTotal($rows, $roleFilter)),
            $fileName
        );
    }

    public function exportPdf(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        [$rows, $roleFilter, $dari, $sampai] = $this->siapkanExport($request, $storeId ?? 0);
        $storeName = OwnerContext::currentStore()?->nama_toko ?? 'Toko Saya';
        $fmt = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Owner.rekap-karyawan.pdf', [
            'rows' => $rows,
            'totals' => $this->hitungTotal($rows, $roleFilter),
            'ringkasan' => $storeId ? $this->report->ringkasanKeuangan([$storeId], $this->report->rentangPeriode($dari, $sampai)) : null,
            'roleFilter' => $roleFilter,
            'dari' => $dari,
            'sampai' => $sampai,
            'storeName' => $storeName,
            'fmt' => $fmt,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('rekap-karyawan-' . $roleFilter . '-' . now()->translatedFormat('Y-m-d') . '.pdf');
    }

    /**
     * @return array{0: Collection, 1: string, 2: ?string, 3: ?string}
     */
    private function siapkanExport(Request $request, int $storeId): array
    {
        $roleFilter = $request->query('role', 'admin');
        if (! in_array($roleFilter, self::ROLE_FILTERS, true)) {
            $roleFilter = 'admin';
        }
        $dari = $request->query('dari');
        $sampai = $request->query('sampai');
        $dari = is_string($dari) && $dari !== '' ? $dari : null;
        $sampai = is_string($sampai) && $sampai !== '' ? $sampai : null;
        $range = $this->report->rentangPeriode($dari, $sampai);

        $staff = $storeId
            ? StoreStaff::with(['user', 'user.role'])->where('store_id', $storeId)->get()
            : collect();
        $rows = $this->bangunRekap($staff, $storeId ? [$storeId] : [], $roleFilter, $range);

        return [$rows, $roleFilter, $dari, $sampai];
    }

    private function bangunRekap(Collection $staff, array $storeIds, string $roleFilter, ?array $range): Collection
    {
        return $staff
            ->map(function (StoreStaff $s) use ($storeIds, $range, $roleFilter) {
                $user = $s->user;
                $base = [
                    'user_id' => (int) $s->user_id,
                    'nama' => $user?->nama_lengkap ?? '-',
                    'email' => $user?->email ?? '-',
                    'role' => self::roleKey($user?->role_id, $user?->role?->nama_role),
                    'status' => $s->status,
                ];

                if (! $storeIds || ! $user) {
                    return array_merge($base, $this->rekapKosong());
                }

                // Setiap baris selalu membawa rekap keuangan dasar + metrik role-nya,
                // agar semua key tersedia di semua filter (semua/admin/produksi/gudang).
                $keuangan = $this->report->rekapKaryawan((int) $s->user_id, $storeIds);
                $rekap = array_merge(
                    $keuangan,
                    match ($base['role']) {
                        'admin' => $this->report->rekapAdmin((int) $s->user_id, $storeIds, $range),
                        'produksi' => $this->report->rekapProduksi((int) $s->user_id, $storeIds, $range),
                        'gudang' => $this->report->rekapGudang((int) $s->user_id, $storeIds, $range),
                        default => [],
                    }
                );

                if ($roleFilter === 'semua') {
                    // Mode semua memakai angka keuangan tak-terfilter seperti semula.
                    $rekap['pesanan'] = $keuangan['pesanan'];
                    $rekap['pendapatan'] = $keuangan['pendapatan'];
                }

                return array_merge($base, $rekap);
            })
            ->when($roleFilter !== 'semua', fn ($rows) => $rows->filter(fn ($r) => $r['role'] === $roleFilter))
            ->values();
    }

    private function rekapKosong(): array
    {
        return [
            'pesanan' => 0, 'pendapatan' => 0.0, 'refund' => 0.0, 'expense' => 0.0,
            'pengeluaran' => 0.0, 'bersih' => 0.0,
            'diverifikasi' => 0, 'ditolak' => 0, 'cr' => null, 'aov' => null,
            'rating' => null, 'rating_count' => 0,
            'ditugaskan' => 0, 'selesai' => 0, 'sukses_persen' => null,
            'rata_unit_diminta' => null, 'rata_output_layak' => null,
            'rata_durasi_jam' => null, 'sampel_durasi' => 0,
            'transfer_diminta' => 0, 'transfer_selesai' => 0, 'transfer_batal' => 0,
            'rata_putaran_jam' => null, 'sampel_putaran' => 0, 'mutasi' => 0,
            'opname' => 0, 'akurasi_persen' => null, 'kerusakan' => 0, 'kerusakan_qty' => 0,
        ];
    }

    private function hitungTotal(Collection $rows, string $roleFilter): array
    {
        if ($roleFilter === 'admin') {
            $n = $rows->count();
            $diverifikasi = (int) $rows->sum('diverifikasi');
            $ditangani = $diverifikasi + (int) $rows->sum('ditolak');
            $pendapatan = (float) $rows->sum('pendapatan');
            $pesanan = (int) $rows->sum('pesanan');
            $ratingCount = (int) $rows->sum('rating_count');

            return [
                'cr' => $ditangani > 0 ? round($diverifikasi / $ditangani * 100, 2) : null,
                'aov' => $pesanan > 0 ? round($pendapatan / $pesanan, 2) : null,
                'rating' => $ratingCount > 0
                    ? round($rows->sum(fn ($r) => ($r['rating'] ?? 0) * $r['rating_count']) / $ratingCount, 2)
                    : null,
                'rating_count' => $ratingCount,
                'pesanan' => $pesanan,
                'pendapatan' => $pendapatan,
                'karyawan' => $n,
            ];
        }

        if ($roleFilter === 'produksi') {
            $ditugaskan = (int) $rows->sum('ditugaskan');
            $selesai = (int) $rows->sum('selesai');

            return [
                'ditugaskan' => $ditugaskan,
                'selesai' => $selesai,
                'sukses_persen' => $ditugaskan > 0 ? round($selesai / $ditugaskan * 100, 2) : null,
            ];
        }

        $opname = (int) $rows->sum('opname');

        return [
            'transfer_diminta' => (int) $rows->sum('transfer_diminta'),
            'transfer_selesai' => (int) $rows->sum('transfer_selesai'),
            'mutasi' => (int) $rows->sum('mutasi'),
            'opname' => $opname,
            'akurasi_persen' => $opname > 0
                ? round($rows->sum(fn ($r) => ($r['akurasi_persen'] ?? 0) * $r['opname']) / $opname, 2)
                : null,
            'kerusakan_qty' => (int) $rows->sum('kerusakan_qty'),
        ];
    }
}
