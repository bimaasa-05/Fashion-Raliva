<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AdminLaporanExport;
use App\Http\Controllers\Controller;
use App\Services\AdminLaporanService;
use App\Services\KaryawanReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function __construct(
        protected KaryawanReportService $karyawanReport,
    ) {
    }

    public function index(Request $request)
    {
        $laporan = AdminLaporanService::fromRequest($request);
        $storeIds = $laporan->storeIds();
        $ringkasan = $laporan->ringkasan();

        // per admin ("Penjualanku") — pendapatan = order yg payment-nya diverifikasi admin ini
        $saya = $storeIds && auth()->check() && auth()->id()
            ? $this->karyawanReport->rekapKaryawan((int) auth()->id(), $storeIds)
            : ['pesanan' => 0, 'pendapatan' => 0.0, 'refund' => 0.0, 'expense' => 0.0, 'pengeluaran' => 0.0, 'bersih' => 0.0];

        return view('Admin.laporan.index', [
            'tanpaToko' => ! $laporan->punyaToko(),
            'dari' => $laporan->dari(),
            'sampai' => $laporan->sampai(),
            'presetAktif' => $laporan->presetAktif(),
            'pendapatan' => $ringkasan['pendapatan'],
            'pesananDiproses' => $ringkasan['pesanan'],
            'totalPengeluaran' => $ringkasan['pengeluaran'],
            'totalBersih' => $ringkasan['bersih'],
            'perMetode' => $laporan->perMetode(),
            'metodeTerbanyak' => $laporan->metodeTerbanyak(),
            'distribusiMetode' => $laporan->distribusiMetode(),
            'omzetBars' => $laporan->omzetBars(),
            'ringkasanHarian' => $laporan->ringkasanHarian(),
            'pesananSelesai' => $laporan->pesananSelesai(10),
            'saya' => $saya,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $laporan = AdminLaporanService::fromRequest($request);

        if (! $laporan->punyaToko()) {
            return back()->with('toast', ['message' => __('Belum ada toko untuk diekspor.'), 'icon' => 'storefront']);
        }

        $fileName = 'laporan-operasional-'.$laporan->dari()->format('Y-m-d').'-'.$laporan->sampai()->format('Y-m-d').'.xlsx';

        return Excel::download(new AdminLaporanExport($laporan), $fileName);
    }

    public function cetak(Request $request)
    {
        return view('Admin.laporan.cetak', $this->dataCetak(AdminLaporanService::fromRequest($request)));
    }

    public function exportPdf(Request $request)
    {
        $laporan = AdminLaporanService::fromRequest($request);

        $pdf = Pdf::loadView('Admin.laporan.pdf', $this->dataCetak($laporan))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-operasional-'.$laporan->dari()->format('Y-m-d').'-'.$laporan->sampai()->format('Y-m-d').'.pdf');
    }

    /**
     * @return array<string, mixed>
     */
    protected function dataCetak(AdminLaporanService $laporan): array
    {
        return [
            'laporan' => $laporan,
            'ringkasan' => $laporan->ringkasan(),
            'harian' => $laporan->harian(),
            'perMetode' => $laporan->perMetode(),
            'pesananSelesai' => $laporan->pesananSelesaiSemua(),
            'fmt' => fn ($nilai) => 'Rp '.number_format((float) $nilai, 0, ',', '.'),
        ];
    }
}
