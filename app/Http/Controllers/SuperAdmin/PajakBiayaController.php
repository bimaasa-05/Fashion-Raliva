<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class PajakBiayaController extends Controller
{
    public function index()
    {
        $komisi = (float) Setting::get(Setting::KOMISI_PERSEN_DEFAULT, '5');
        $pajak = (float) Setting::get(Setting::PAJAK_PERSEN, '11');

        return view('SuperAdmin.pajak-biaya.index', [
            'komisi' => $komisi,
            'pajak' => $pajak,
        ]);
    }

    public function updatePajak(Request $request)
    {
        $data = $request->validate([
            'pajak_persen' => 'required|numeric|min:0|max:50',
        ], [
            'pajak_persen.required' => 'Tarif pajak wajib diisi.',
            'pajak_persen.numeric' => 'Tarif pajak harus berupa angka.',
            'pajak_persen.min' => 'Tarif pajak minimal 0%.',
            'pajak_persen.max' => 'Tarif pajak maksimal 50%.',
        ]);

        $nilaiBaru = (string) $data['pajak_persen'];
        $nilaiLama = Setting::get(Setting::PAJAK_PERSEN, '11');

        if ($nilaiBaru === $nilaiLama) {
            return back()->with('toast', [
                'message' => 'Tidak ada perubahan — tarif pajak sudah '.number_format((float) $nilaiBaru, 0, ',', '.').'%.',
                'icon' => 'info',
            ]);
        }

        Setting::set(Setting::PAJAK_PERSEN, $nilaiBaru);

        ActivityLogger::log(
            'setting.pajak.update',
            Setting::class,
            null,
            ['nilai' => $nilaiLama],
            ['nilai' => $nilaiBaru],
            sprintf('Mengubah tarif pajak dari %s%% menjadi %s%%.', number_format((float) $nilaiLama, 0, ',', '.'), number_format((float) $nilaiBaru, 0, ',', '.'))
        );

        return back()->with('toast', [
            'message' => 'Tarif pajak berhasil diperbarui menjadi '.number_format((float) $nilaiBaru, 0, ',', '.').'%.',
            'icon' => 'task_alt',
        ]);
    }
}
