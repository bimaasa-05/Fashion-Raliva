<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Setting;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengaturanSistemController extends Controller
{
    public function index()
    {
        $rawTier = Setting::get(Setting::PERINGKAT_TIER, null);
        $tiers = \App\Support\PeringkatService::defaultTiers();
        if ($rawTier) {
            $decoded = json_decode($rawTier, true);
            if (is_array($decoded) && $decoded !== []) $tiers = $decoded;
        }

        return view('SuperAdmin.pengaturan-sistem.index', [
            'syaratKetentuan' => Setting::get(Setting::SYARAT_KETENTUAN, ''),
            'kebijakanPrivasi' => Setting::get(Setting::KEBIJAKAN_PRIVASI, ''),
            'settings' => [
                'nama_platform' => Setting::get(Setting::NAMA_PLATFORM, 'Raliva'),
                'email_support' => Setting::get(Setting::EMAIL_SUPPORT, 'support@raliva.com'),
                'komisi_persen_default' => Setting::get(Setting::KOMISI_PERSEN_DEFAULT, '5'),
                'biaya_layanan' => Setting::get(Setting::BIAYA_LAYANAN, '1000'),
                'min_pencairan' => Setting::get(Setting::MIN_PENCAIRAN, '50000'),
                'mode_maintenance' => Setting::get(Setting::MODE_MAINTENANCE, '0'),
                'moderasi_otomatis' => Setting::get(Setting::MODERASI_OTOMATIS, '1'),
                'maks_pengajuan_pencairan' => Setting::get('maks_pengajuan_pencairan', '3'),
                'batas_waktu_refund' => Setting::get('batas_waktu_refund', '7'),
            ],
            'tiers' => $tiers,
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'nama_platform' => 'sometimes|required|string|max:100',
            'email_support' => 'sometimes|required|email|max:100',
            'komisi_persen_default' => 'sometimes|required|numeric|min:0|max:15',
            'biaya_layanan' => 'sometimes|required|numeric|min:0',
            'min_pencairan' => 'sometimes|required|numeric|min:0',
            'mode_maintenance' => 'sometimes|nullable|in:0,1',
            'moderasi_otomatis' => 'sometimes|nullable|in:0,1',
            'maks_pengajuan_pencairan' => 'sometimes|nullable|numeric|min:1',
            'batas_waktu_refund' => 'sometimes|nullable|numeric|min:1',
        ]);

        $map = [
            'nama_platform' => Setting::NAMA_PLATFORM,
            'email_support' => Setting::EMAIL_SUPPORT,
            'komisi_persen_default' => Setting::KOMISI_PERSEN_DEFAULT,
            'biaya_layanan' => Setting::BIAYA_LAYANAN,
            'min_pencairan' => Setting::MIN_PENCAIRAN,
            'mode_maintenance' => Setting::MODE_MAINTENANCE,
            'moderasi_otomatis' => Setting::MODERASI_OTOMATIS,
            'maks_pengajuan_pencairan' => 'maks_pengajuan_pencairan',
            'batas_waktu_refund' => 'batas_waktu_refund',
        ];

        $lama = [];
        $baru = [];
        DB::transaction(function () use ($map, $data, &$lama, &$baru) {
            foreach ($map as $field => $key) {
                if (! array_key_exists($field, $data)) {
                    continue;
                }

                $value = (string) $data[$field];

                Setting::where('kunci', $key)->lockForUpdate()->get();

                $lama[$field] = Setting::get($key);
                Setting::set($key, $value);
                $baru[$field] = $value;
            }

            if (empty($baru)) {
                return;
            }

            ActivityLogger::log(
                'setting.system.update',
                Setting::class,
                null,
                ['nilai_lama' => $lama],
                ['nilai_baru' => $baru],
                'Memperbarui pengaturan sistem platform.'
            );

            Notification::fireSelf(Notification::TIPE_SISTEM, 'Pengaturan Sistem Diperbarui', 'Pengaturan sistem platform berhasil disimpan.', route('superadmin.pengaturan-sistem'));
        });

        if (empty($baru)) {
            return back()->with('toast', ['message' => 'Tidak ada pengaturan yang dikirim.', 'icon' => 'info']);
        }

        return back()->with('toast', [
            'message' => 'Pengaturan sistem berhasil disimpan.',
            'icon' => 'task_alt',
        ]);
    }

    public function updateLegal(Request $request)
    {
        $data = $request->validate([
            'syarat_ketentuan' => 'required|string|min:10',
            'kebijakan_privasi' => 'required|string|min:10',
        ], [
            'required' => ':attribute wajib diisi.',
            'min' => ':attribute minimal 10 karakter.',
        ], [
            'syarat_ketentuan' => 'Syarat & Ketentuan',
            'kebijakan_privasi' => 'Kebijakan Privasi',
        ]);

        $lama = [
            'syarat_ketentuan' => Setting::get(Setting::SYARAT_KETENTUAN),
            'kebijakan_privasi' => Setting::get(Setting::KEBIJAKAN_PRIVASI),
        ];

        Setting::set(Setting::SYARAT_KETENTUAN, $data['syarat_ketentuan']);
        Setting::set(Setting::KEBIJAKAN_PRIVASI, $data['kebijakan_privasi']);

        ActivityLogger::log(
            'setting.legal.update',
            Setting::class,
            null,
            ['nilai_lama' => $lama],
            ['nilai_baru' => $data],
            'Memperbarui Syarat & Ketentuan dan Kebijakan Privasi platform.'
        );

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Dokumen Legal Diperbarui', 'Syarat & Ketentuan dan Kebijakan Privasi diperbarui.', route('superadmin.pengaturan-sistem'));

        return back()->with('toast', [
            'message' => 'Konten Syarat & Ketentuan dan Kebijakan Privasi berhasil disimpan.',
            'icon' => 'task_alt',
        ]);
    }

    public function updateTier(Request $request)
    {
        $data = $request->validate([
            'tiers' => 'required|array|min:1',
            'tiers.*.min' => 'required|integer|min:100000',
            'tiers.*.max' => 'nullable|integer|min:100000',
            'tiers.*.hari' => 'required|integer|min:1|max:365',
        ]);

        $tiers = collect($data['tiers'])->sortBy('min')->values()->all();

        foreach ($tiers as $i => $t) {
            if ($i > 0) {
                $prevMax = $tiers[$i - 1]['max'];
                if ($prevMax !== null && $t['min'] <= $prevMax) {
                    return back()->with('toast', ['message' => 'Tier tumpang tindih pada baris '.($i + 1).'.', 'icon' => 'gpp_maybe']);
                }
            }
            if ($t['max'] !== null && $t['max'] < $t['min']) {
                return back()->with('toast', ['message' => 'Max harus >= min pada baris '.($i + 1).'.', 'icon' => 'gpp_maybe']);
            }
        }

        $nullCount = collect($tiers)->whereNull('max')->count();
        if ($nullCount > 1) {
            return back()->with('toast', ['message' => 'Hanya tier terakhir boleh Max kosong (∞).', 'icon' => 'gpp_maybe']);
        }
        if ($nullCount === 1 && end($tiers)['max'] !== null) {
            return back()->with('toast', ['message' => 'Tier dengan Max ∞ harus di urutan terakhir.', 'icon' => 'gpp_maybe']);
        }

        $old = Setting::get(Setting::PERINGKAT_TIER, null);
        Setting::set(Setting::PERINGKAT_TIER, json_encode($tiers));
        ActivityLogger::log('setting.peringkat_tier.update', Setting::class, 0, ['nilai_lama' => $old], ['nilai_baru' => $tiers], 'Mengubah tier peringkat iklan.');
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Tier Peringkat Diperbarui', 'Tier peringkat iklan berhasil diperbarui.', route('superadmin.pengaturan-sistem'));

        return back()->with('toast', ['message' => 'Tier peringkat berhasil diperbarui.', 'icon' => 'task_alt']);
    }

    public function storeTier(Request $request)
    {
        $data = $request->validate([
            'min' => 'required|integer|min:100000',
            'max' => 'nullable|integer|min:100000',
            'hari' => 'required|integer|min:1|max:365',
        ]);

        $raw = Setting::get(Setting::PERINGKAT_TIER, null);
        $tiers = $raw ? json_decode($raw, true) : \App\Support\PeringkatService::defaultTiers();
        if (! is_array($tiers)) $tiers = \App\Support\PeringkatService::defaultTiers();

        $tiers[] = ['min' => (int) $data['min'], 'max' => $data['max'] !== null ? (int) $data['max'] : null, 'hari' => (int) $data['hari']];
        $tiers = collect($tiers)->sortBy('min')->values()->all();

        foreach ($tiers as $i => $t) {
            if ($i > 0) {
                $prevMax = $tiers[$i - 1]['max'];
                if ($prevMax !== null && $t['min'] <= $prevMax) {
                    return back()->with('toast', ['message' => 'Tier tumpang tindih.', 'icon' => 'gpp_maybe']);
                }
            }
        }

        $nullCount = collect($tiers)->whereNull('max')->count();
        if ($nullCount > 1) {
            return back()->with('toast', ['message' => 'Hanya tier terakhir boleh Max kosong.', 'icon' => 'gpp_maybe']);
        }

        Setting::set(Setting::PERINGKAT_TIER, json_encode($tiers));
        ActivityLogger::log('setting.peringkat_tier.create', Setting::class, 0, null, $data, 'Menambah tier peringkat.');
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Tier Ditambahkan', 'Tier peringkat baru ditambahkan.', route('superadmin.pengaturan-sistem'));

        return back()->with('toast', ['message' => 'Tier berhasil ditambahkan.', 'icon' => 'task_alt']);
    }

    public function updateSingleTier(Request $request, int $index)
    {
        $data = $request->validate([
            'min' => 'required|integer|min:100000',
            'max' => 'nullable|integer|min:100000',
            'hari' => 'required|integer|min:1|max:365',
        ]);

        $raw = Setting::get(Setting::PERINGKAT_TIER, null);
        $tiers = $raw ? json_decode($raw, true) : \App\Support\PeringkatService::defaultTiers();
        if (! is_array($tiers) || ! isset($tiers[$index])) {
            return back()->with('toast', ['message' => 'Tier tidak ditemukan.', 'icon' => 'gpp_maybe']);
        }

        $old = $tiers[$index];
        $tiers[$index] = ['min' => (int) $data['min'], 'max' => $data['max'] !== null ? (int) $data['max'] : null, 'hari' => (int) $data['hari']];
        $tiers = collect($tiers)->sortBy('min')->values()->all();

        foreach ($tiers as $i => $t) {
            if ($i > 0) {
                $prevMax = $tiers[$i - 1]['max'];
                if ($prevMax !== null && $t['min'] <= $prevMax) {
                    return back()->with('toast', ['message' => 'Tier tumpang tindih pada baris '.($i + 1).'.', 'icon' => 'gpp_maybe']);
                }
            }
            if ($t['max'] !== null && $t['max'] < $t['min']) {
                return back()->with('toast', ['message' => 'Max harus >= min.', 'icon' => 'gpp_maybe']);
            }
        }

        Setting::set(Setting::PERINGKAT_TIER, json_encode($tiers));
        ActivityLogger::log('setting.peringkat_tier.update', Setting::class, $index, ['nilai_lama' => $old], ['nilai_baru' => $tiers[$index]], 'Mengubah tier peringkat index '.$index.'.');
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Tier Diperbarui', 'Tier peringkat diperbarui.', route('superadmin.pengaturan-sistem'));

        return back()->with('toast', ['message' => 'Tier berhasil diperbarui.', 'icon' => 'task_alt']);
    }

    public function destroyTier(int $index)
    {
        $raw = Setting::get(Setting::PERINGKAT_TIER, null);
        $tiers = $raw ? json_decode($raw, true) : \App\Support\PeringkatService::defaultTiers();
        if (! is_array($tiers) || ! isset($tiers[$index])) {
            return back()->with('toast', ['message' => 'Tier tidak ditemukan.', 'icon' => 'gpp_maybe']);
        }
        if (count($tiers) <= 1) {
            return back()->with('toast', ['message' => 'Minimal harus ada 1 tier.', 'icon' => 'gpp_maybe']);
        }

        $removed = $tiers[$index];
        array_splice($tiers, $index, 1);
        $tiers = array_values($tiers);

        Setting::set(Setting::PERINGKAT_TIER, json_encode($tiers));
        ActivityLogger::log('setting.peringkat_tier.delete', Setting::class, $index, ['nilai_lama' => $removed], null, 'Menghapus tier peringkat index '.$index.'.');
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Tier Dihapus', 'Tier peringkat dihapus.', route('superadmin.pengaturan-sistem'));

        return back()->with('toast', ['message' => 'Tier berhasil dihapus.', 'icon' => 'task_alt']);
    }
}
