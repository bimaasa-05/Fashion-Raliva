<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Role;
use Illuminate\Http\Request;

class RiwayatAktivitasController extends Controller
{
    public function index()
    {
        session(['sa_activity_seen' => (int) ActivityLog::max('activity_log_id')]);

        $query = ActivityLog::with('user:user_id,nama_lengkap')
            ->whereHas('user', fn ($q) => $q->whereHas('role', fn ($r) => $r->where('nama_role', Role::SUPER_ADMIN)))
            ->orderByDesc('activity_log_id');

        if ($kategori = request('kategori')) {
            $this->applyKategoriFilter($query, $kategori);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('SuperAdmin.riwayat-aktivitas.index', [
            'logs' => $logs,
        ]);
    }

    public function baru(Request $request)
    {
        $since = (int) $request->query('since', 0);

        $logs = ActivityLog::with('user:user_id,nama_lengkap')
            ->whereHas('user', fn ($q) => $q->whereHas('role', fn ($r) => $r->where('nama_role', Role::SUPER_ADMIN)))
            ->where('activity_log_id', '>', $since)
            ->orderBy('activity_log_id')
            ->limit(20)
            ->get()
            ->map(fn (ActivityLog $log) => $this->payload($log));

        return response()->json($logs);
    }

    private function payload(ActivityLog $log): array
    {
        $prefix = explode('.', $log->aksi)[0] ?? 'system';

        $iconMap = [
            'user' => 'person', 'store' => 'storefront', 'product' => 'inventory_2',
            'order' => 'shopping_cart', 'withdrawal' => 'account_balance', 'refund' => 'currency_exchange',
            'commission' => 'percent', 'wallet' => 'account_balance_wallet', 'setting' => 'settings',
            'system' => 'settings', 'payment' => 'payments', 'promo' => 'local_offer',
            'slot' => 'grid_view', 'adslot' => 'campaign', 'complaint' => 'support_agent',
        ];
        $tagMap = [
            'user' => 'Pengguna', 'store' => 'Toko', 'product' => 'Produk', 'order' => 'Pesanan',
            'withdrawal' => 'Keuangan', 'refund' => 'Keuangan', 'commission' => 'Keuangan',
            'wallet' => 'Keuangan', 'setting' => 'Sistem', 'system' => 'Sistem', 'payment' => 'Keuangan',
            'promo' => 'Promo', 'slot' => 'Produk', 'adslot' => 'Iklan', 'complaint' => 'Komplain',
        ];

        return [
            'id' => $log->activity_log_id,
            'aksi' => $log->aksi,
            'icon' => $iconMap[$prefix] ?? 'info',
            'tag' => $tagMap[$prefix] ?? ucfirst($prefix),
            'waktu' => $log->created_at ? $log->created_at->locale('id')->diffForHumans() : '-',
            'waktu_iso' => $log->created_at?->toIso8601String(),
            'user' => $log->user?->nama_lengkap,
            'deskripsi' => (string) ($log->deskripsi ?? $log->aksi),
            'ada_perubahan' => (bool) ($log->nilai_lama || $log->nilai_baru),
        ];
    }

    public function export(Request $request)
    {
        $query = ActivityLog::with('user:user_id,nama_lengkap')
            ->whereHas('user', fn ($q) => $q->whereHas('role', fn ($r) => $r->where('nama_role', Role::SUPER_ADMIN)))
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
            'produk' => ['product.', 'slot.', 'adslot.'],
            'keuangan' => ['order.', 'withdrawal.', 'refund.', 'commission.', 'wallet.'],
            'sistem' => ['setting.', 'system.', 'commission.update', 'promo.', 'complaint.'],
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
