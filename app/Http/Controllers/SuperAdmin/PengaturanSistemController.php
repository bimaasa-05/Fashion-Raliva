<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class PengaturanSistemController extends Controller
{
    public function index()
    {
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
            ],
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'nama_platform' => 'required|string|max:100',
            'email_support' => 'required|email|max:100',
            'komisi_persen_default' => 'required|numeric|min:0|max:100',
            'biaya_layanan' => 'required|numeric|min:0',
            'min_pencairan' => 'required|numeric|min:0',
            'mode_maintenance' => 'nullable|in:0,1',
            'moderasi_otomatis' => 'nullable|in:0,1',
            'maks_pengajuan_pencairan' => 'nullable|numeric|min:1',
            'batas_waktu_refund' => 'nullable|numeric|min:1',
        ]);

        $old = [
            'nama_platform' => Setting::get(Setting::NAMA_PLATFORM),
            'email_support' => Setting::get(Setting::EMAIL_SUPPORT),
            'komisi_persen_default' => Setting::get(Setting::KOMISI_PERSEN_DEFAULT),
            'biaya_layanan' => Setting::get(Setting::BIAYA_LAYANAN),
            'min_pencairan' => Setting::get(Setting::MIN_PENCAIRAN),
        ];

        Setting::set(Setting::NAMA_PLATFORM, $data['nama_platform']);
        Setting::set(Setting::EMAIL_SUPPORT, $data['email_support']);
        Setting::set(Setting::KOMISI_PERSEN_DEFAULT, (string) $data['komisi_persen_default']);
        Setting::set(Setting::BIAYA_LAYANAN, (string) $data['biaya_layanan']);
        Setting::set(Setting::MIN_PENCAIRAN, (string) $data['min_pencairan']);
        Setting::set(Setting::MODE_MAINTENANCE, $data['mode_maintenance'] ?? '0');
        Setting::set(Setting::MODERASI_OTOMATIS, $data['moderasi_otomatis'] ?? '1');

        if (isset($data['maks_pengajuan_pencairan'])) {
            Setting::set('maks_pengajuan_pencairan', (string) $data['maks_pengajuan_pencairan']);
        }
        if (isset($data['batas_waktu_refund'])) {
            Setting::set('batas_waktu_refund', (string) $data['batas_waktu_refund']);
        }

        ActivityLogger::log(
            'setting.system.update',
            Setting::class,
            null,
            ['nilai_lama' => $old],
            ['nilai_baru' => $data],
            'Memperbarui pengaturan sistem platform.'
        );

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

        return back()->with('toast', [
            'message' => 'Konten Syarat & Ketentuan dan Kebijakan Privasi berhasil disimpan.',
            'icon' => 'task_alt',
        ]);
    }
}
