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
