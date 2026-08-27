<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Commission;
use App\Models\Setting;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class KomisiGlobalController extends Controller
{
    public function index()
    {
        $komisi = (float) Setting::get(Setting::KOMISI_PERSEN_DEFAULT, '5');

        $bulanIni = now()->startOfMonth();
        $bulanLalu = now()->subMonth()->startOfMonth();

        $transaksiBulanIni = Commission::where('created_at', '>=', $bulanIni)->count();
        $transaksiBulanLalu = Commission::where('created_at', '>=', $bulanLalu)->where('created_at', '<', $bulanIni)->count();
        $tokoTerdampak = Commission::where('created_at', '>=', $bulanIni)->distinct('store_id')->count('store_id');

        $estimasiKomisi = Commission::where('created_at', '>=', $bulanIni)->sum('jumlah_komisi');
        $estimasiBulanLalu = Commission::where('created_at', '>=', $bulanLalu)->where('created_at', '<', $bulanIni)->sum('jumlah_komisi');

        $persenChangeTransaksi = $transaksiBulanLalu > 0
            ? round((($transaksiBulanIni - $transaksiBulanLalu) / $transaksiBulanLalu) * 100, 1)
            : 0;

        $persenChangeKomisi = $estimasiBulanLalu > 0
            ? round((($estimasiKomisi - $estimasiBulanLalu) / $estimasiBulanLalu) * 100, 1)
            : 0;

        $perubahanTarif = ActivityLog::where('aksi', 'like', 'setting.komisi%')->count();

        $riwayat = ActivityLog::where('target_tipe', Setting::class)
            ->where('aksi', 'like', 'setting.komisi%')
            ->with('user:user_id,nama_lengkap')
            ->orderByDesc('activity_log_id')
            ->limit(10)
            ->get()
            ->map(fn ($log) => [
                'tanggal' => $log->created_at,
                'deskripsi' => $log->deskripsi,
                'aksi' => str_contains($log->aksi, 'update') ? 'ubah' : 'inisial',
                'nilai_lama' => $log->nilai_lama['nilai'] ?? null,
                'nilai_baru' => $log->nilai_baru['nilai'] ?? null,
                'oleh' => $log->user->nama_lengkap ?? 'Sistem',
            ]);

        return view('SuperAdmin.komisi-global.index', [
            'komisi' => $komisi,
            'stats' => [
                'estimasi_komisi' => $estimasiKomisi,
                'transaksi' => $transaksiBulanIni,
                'toko' => $tokoTerdampak,
                'perubahan' => $perubahanTarif,
                'persen_komisi' => $persenChangeKomisi,
                'persen_transaksi' => $persenChangeTransaksi,
            ],
            'riwayat' => $riwayat,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'komisi_persen' => 'required|numeric|min:0|max:15',
            'catatan' => 'nullable|string|max:500',
        ], [
            'komisi_persen.required' => 'Tarif komisi wajib diisi.',
            'komisi_persen.numeric' => 'Tarif komisi harus berupa angka.',
            'komisi_persen.min' => 'Tarif komisi minimal 0%.',
            'komisi_persen.max' => 'Tarif komisi maksimal 15%.',
        ]);

        $nilaiBaru = (string) $data['komisi_persen'];
        $nilaiLama = Setting::get(Setting::KOMISI_PERSEN_DEFAULT, '5');

        if ($nilaiBaru === $nilaiLama) {
            return back()->with('toast', [
                'message' => 'Tidak ada perubahan — tarif komisi sudah '.number_format((float) $nilaiBaru, 0, ',', '.').'%.',
                'icon' => 'info',
            ]);
        }

        Setting::set(Setting::KOMISI_PERSEN_DEFAULT, $nilaiBaru);

        ActivityLogger::log(
            'setting.komisi.update',
            Setting::class,
            null,
            ['nilai' => $nilaiLama],
            ['nilai' => $nilaiBaru],
            sprintf('Mengubah tarif komisi global dari %s%% menjadi %s%%.%s', number_format((float) $nilaiLama, 0, ',', '.'), number_format((float) $nilaiBaru, 0, ',', '.'), $data['catatan'] ? ' Catatan: '.$data['catatan'] : '')
        );

        return back()->with('toast', [
            'message' => 'Tarif komisi global berhasil diperbarui menjadi '.number_format((float) $nilaiBaru, 0, ',', '.').'%.',
            'icon' => 'task_alt',
        ]);
    }
}
