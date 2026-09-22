<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ProductSlotPackage;
use App\Models\SlotPackagePromotion;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SlotPromoController extends Controller
{
    public function index()
    {
        $promos = SlotPackagePromotion::with('package')
            ->orderByDesc('created_at')
            ->get();

        $packages = ProductSlotPackage::orderBy('jumlah_slot')
            ->get(['slot_package_id', 'nama_paket', 'harga', 'jumlah_slot', 'status']);

        return view('SuperAdmin.promo-slot.index', [
            'promos' => $promos,
            'packages' => $packages,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slot_package_id' => 'required|integer|exists:product_slot_packages,slot_package_id',
            'nama_promo' => 'required|string|max:150',
            'kode_promo' => 'required|string|max:100|unique:slot_package_promotions,kode_promo',
            'tipe_diskon' => 'required|in:persen,nominal',
            'nilai_diskon' => 'required|numeric|min:1',
            'maksimal_diskon' => 'nullable|numeric|min:0',
            'mulai_pada' => 'required|date',
            'berakhir_pada' => 'required|date|after:mulai_pada',
            'deskripsi' => 'nullable|string',
        ], [
            'slot_package_id.required' => 'Pilih paket slot.',
            'nama_promo.required' => 'Nama promo wajib diisi.',
            'kode_promo.required' => 'Kode promo wajib diisi.',
            'kode_promo.unique' => 'Kode promo sudah digunakan.',
            'tipe_diskon.required' => 'Tipe diskon wajib dipilih.',
            'nilai_diskon.required' => 'Nilai diskon wajib diisi.',
            'berakhir_pada.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
        ]);

        $promo = SlotPackagePromotion::create([
            'creator_id' => Auth::id(),
            'slot_package_id' => $data['slot_package_id'],
            'nama_promo' => $data['nama_promo'],
            'kode_promo' => strtoupper($data['kode_promo']),
            'tipe_diskon' => $data['tipe_diskon'],
            'nilai_diskon' => $data['nilai_diskon'],
            'maksimal_diskon' => $data['maksimal_diskon'] ?? null,
            'mulai_pada' => $data['mulai_pada'],
            'berakhir_pada' => $data['berakhir_pada'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'status' => SlotPackagePromotion::STATUS_AKTIF,
        ]);

        ActivityLogger::log(
            'promo.create',
            SlotPackagePromotion::class,
            $promo->slot_promo_id,
            null,
            $promo->toArray(),
            'Membuat promo slot baru: '.$promo->nama_promo
        );

        Notification::fireSelf(Notification::TIPE_PROMO, 'Promo Slot Dibuat', 'Promo slot "'.$promo->nama_promo.'" dibuat.', route('superadmin.promo-slot'));

        return back()->with('toast', [
            'message' => 'Promo slot "'.$promo->nama_promo.'" berhasil dibuat.',
            'icon' => 'task_alt',
        ]);
    }

    public function getDetail(SlotPackagePromotion $promo)
    {
        $promo->load('package');

        return response()->json($promo);
    }

    public function update(Request $request, SlotPackagePromotion $promo)
    {
        $data = $request->validate([
            'slot_package_id' => 'required|integer|exists:product_slot_packages,slot_package_id',
            'nama_promo' => 'required|string|max:150',
            'kode_promo' => 'required|string|max:100|unique:slot_package_promotions,kode_promo,'.$promo->slot_promo_id.',slot_promo_id',
            'tipe_diskon' => 'required|in:persen,nominal',
            'nilai_diskon' => 'required|numeric|min:1',
            'maksimal_diskon' => 'nullable|numeric|min:0',
            'mulai_pada' => 'required|date',
            'berakhir_pada' => 'required|date|after:mulai_pada',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $lama = $promo->toArray();

        $promo->update([
            'slot_package_id' => $data['slot_package_id'],
            'nama_promo' => $data['nama_promo'],
            'kode_promo' => strtoupper($data['kode_promo']),
            'tipe_diskon' => $data['tipe_diskon'],
            'nilai_diskon' => $data['nilai_diskon'],
            'maksimal_diskon' => $data['maksimal_diskon'] ?? null,
            'mulai_pada' => $data['mulai_pada'],
            'berakhir_pada' => $data['berakhir_pada'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'status' => $data['status'],
        ]);

        ActivityLogger::log(
            'promo.update',
            SlotPackagePromotion::class,
            $promo->slot_promo_id,
            $lama,
            $promo->fresh()->toArray(),
            'Memperbarui promo slot: '.$promo->nama_promo
        );

        Notification::fireSelf(Notification::TIPE_PROMO, 'Promo Slot Diperbarui', 'Promo slot "'.$promo->nama_promo.'" diperbarui.', route('superadmin.promo-slot'));

        return back()->with('toast', [
            'message' => 'Promo slot "'.$promo->nama_promo.'" berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function destroy(SlotPackagePromotion $promo)
    {
        $nama = $promo->nama_promo;

        ActivityLogger::log(
            'promo.delete',
            SlotPackagePromotion::class,
            $promo->slot_promo_id,
            $promo->toArray(),
            null,
            'Menghapus promo slot: '.$nama
        );

        $promo->delete();

        Notification::fireSelf(Notification::TIPE_PROMO, 'Promo Slot Dihapus', 'Promo slot "'.$nama.'" dihapus.', route('superadmin.promo-slot'));

        return back()->with('toast', [
            'message' => 'Promo slot "'.$nama.'" berhasil dihapus.',
            'icon' => 'task_alt',
        ]);
    }
}
