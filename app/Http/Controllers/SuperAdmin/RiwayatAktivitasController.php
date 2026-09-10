<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RiwayatAktivitasController extends Controller
{
    public function index()
    {
        $query = ActivityLog::with('user:user_id,nama_lengkap')
            ->orderByDesc('activity_log_id');

        if ($kategori = request('kategori')) {
            $this->applyKategoriFilter($query, $kategori);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('SuperAdmin.riwayat-aktivitas.index', [
            'logs' => $logs,
        ]);
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with('user:user_id,nama_lengkap')
            ->orderByDesc('activity_log_id');

        $kategori = $request->query('kategori');
        if ($kategori) {
            $this->applyKategoriFilter($query, $kategori);
        }

        $logs = $query->get();

        $fileName = 'riwayat-aktivitas-'.($kategori ?: 'semua').'-'.now()->format('Y-m-d').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ];

        $callback = function () use ($logs) {
            $out = fopen('php://output', 'w');
            fwrite($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, ['Waktu', 'Aksi', 'Pengguna', 'Deskripsi', 'Kategori', 'Perubahan']);

            $kategoriTagMap = [
                'user' => 'Pengguna',
                'store' => 'Toko',
                'product' => 'Produk',
                'order' => 'Keuangan',
                'withdrawal' => 'Keuangan',
                'refund' => 'Keuangan',
                'commission' => 'Keuangan',
                'wallet' => 'Keuangan',
                'setting' => 'Sistem',
                'system' => 'Sistem',
                'payment' => 'Pembayaran',
            ];

            foreach ($logs as $log) {
                $prefix = explode('.', $log->aksi)[0] ?? 'system';
                $perubahan = null;
                $nilaiLama = is_array($log->nilai_lama) ? json_encode($log->nilai_lama) : $log->nilai_lama;
                $nilaiBaru = is_array($log->nilai_baru) ? json_encode($log->nilai_baru) : $log->nilai_baru;
                if ($log->nilai_lama || $log->nilai_baru) {
                    $perubahan = 'Data historis tercatat';
                }

                fputcsv($out, [
                    $log->created_at ? $log->created_at->translatedFormat('Y-m-d H:i') : '-',
                    $log->aksi,
                    $log->user?->nama_lengkap ?: '-',
                    strip_tags((string) ($log->deskripsi ?? '-')),
                    $kategoriTagMap[$prefix] ?? ucfirst($prefix),
                    $perubahan ? $perubahan.' (lama: '.($nilaiLama ?: '-').' → baru: '.($nilaiBaru ?: '-').')' : '-',
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function applyKategoriFilter($query, string $kategori): void
    {
        $prefixMap = [
            'pengguna' => 'user.',
            'toko' => 'store.',
            'produk' => 'product.',
            'keuangan' => ['order.', 'withdrawal.', 'refund.', 'commission.', 'wallet.'],
            'sistem' => ['setting.', 'system.', 'commission.update'],
        ];
        $prefix = $prefixMap[$kategori] ?? null;
        if (! $prefix) {
            return;
        }

        if (is_array($prefix)) {
            $query->where(function ($q) use ($prefix) {
                foreach ($prefix as $p) {
                    $q->orWhere('aksi', 'like', $p.'%');
                }
            });
        } else {
            $query->where('aksi', 'like', $prefix.'%');
        }
    }
}
