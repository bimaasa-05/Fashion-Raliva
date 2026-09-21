<?php

namespace App\Http\Controllers\Owner;

use App\Exports\OwnerRekapKaryawanExport;
use App\Http\Controllers\Controller;
use App\Models\StoreStaff;
use App\Services\KaryawanReportService;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class RekapKaryawanController extends Controller
{
    public function __construct(
        protected KaryawanReportService $report,
    ) {
    }

    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $staff = $storeId
            ? StoreStaff::with(['user', 'user.role'])->where('store_id', $storeId)->get()
            : collect();

        $rows = $this->bangunRekap($staff, $storeId);

        $roleFilter = $request->query('role', 'semua');
        if (in_array($roleFilter, ['admin', 'produksi', 'gudang'], true)) {
            $rows = $rows->filter(fn ($r) => $r['role'] === $roleFilter)->values();
        }

        $totals = [
            'pesanan' => $rows->sum('pesanan'),
            'pendapatan' => $rows->sum('pendapatan'),
            'refund' => $rows->sum('refund'),
            'expense' => $rows->sum('expense'),
            'pengeluaran' => $rows->sum('pengeluaran'),
            'bersih' => $rows->sum('bersih'),
        ];

        $storeName = OwnerContext::currentStore()?->nama_toko ?? 'Toko Saya';

        return view('Owner.rekap-karyawan.index', compact('rows', 'totals', 'roleFilter', 'storeName', 'staff'));
    }

    public function exportExcel(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();
        if (! $storeId) {
            return back()->with('toast', ['message' => 'Belum ada toko untuk diekspor.', 'icon' => 'storefront']);
        }

        $staff = StoreStaff::with(['user'])->where('store_id', $storeId)->get();
        $rows = $this->bangunRekap($staff, $storeId);

        $roleFilter = $request->query('role', 'semua');
        if (in_array($roleFilter, ['admin', 'produksi', 'gudang'], true)) {
            $rows = $rows->filter(fn ($r) => $r['role'] === $roleFilter)->values();
        }

        $fileName = 'rekap-karyawan-' . now()->translatedFormat('Y-m-d') . '.xlsx';

        return Excel::download(new OwnerRekapKaryawanExport($rows->all(), OwnerContext::currentStore()?->nama_toko ?? 'Toko Saya'), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $staff = $storeId
            ? StoreStaff::with(['user'])->where('store_id', $storeId)->get()
            : collect();
        $rows = $this->bangunRekap($staff, $storeId);

        $totals = [
            'pesanan' => $rows->sum('pesanan'),
            'pendapatan' => $rows->sum('pendapatan'),
            'refund' => $rows->sum('refund'),
            'expense' => $rows->sum('expense'),
            'pengeluaran' => $rows->sum('pengeluaran'),
            'bersih' => $rows->sum('bersih'),
        ];

        $storeName = OwnerContext::currentStore()?->nama_toko ?? 'Toko Saya';
        $fmt = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('Owner.rekap-karyawan.pdf', compact('rows', 'totals', 'storeName', 'fmt'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('rekap-karyawan-' . now()->translatedFormat('Y-m-d') . '.pdf');
    }

    private function bangunRekap(Collection $staff, ?int $storeId): Collection
    {
        $storeIds = $storeId ? [$storeId] : [];

        return $staff
            ->map(function (StoreStaff $s) use ($storeIds) {
                $user = $s->user;
                $nama = $user?->nama_lengkap ?? '-';
                $email = $user?->email ?? '-';
                $role = match ($user?->role_id) {
                    3 => 'admin',
                    4 => 'produksi',
                    5 => 'gudang',
                    default => 'lainnya',
                };

                $rekap = $storeIds ? $this->report->rekapKaryawan((int) $s->user_id, $storeIds) : [
                    'pesanan' => 0,
                    'pendapatan' => 0.0,
                    'refund' => 0.0,
                    'expense' => 0.0,
                    'pengeluaran' => 0.0,
                    'bersih' => 0.0,
                ];

                return array_merge([
                    'user_id' => (int) $s->user_id,
                    'nama' => $nama,
                    'email' => $email,
                    'role' => $role,
                    'status' => $s->status,
                ], $rekap);
            })
            ->sortByDesc('pendapatan')
            ->values();
    }
}