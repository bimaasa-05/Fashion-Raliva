<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromoPlatformController extends Controller
{
    public function index()
    {
        $promos = Promotion::with(['products.product', 'categories.category'])
            ->orderByDesc('created_at')
            ->get();

        return view('SuperAdmin.promo-platform.index', [
            'promos' => $promos,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_promo' => 'required|string|max:150',
            'kode_promo' => 'required|string|max:100|unique:promotions,kode_promo',
            'tipe_diskon' => 'required|in:persen,nominal',
            'nilai_diskon' => 'required|numeric|min:0',
            'minimal_pembelian' => 'nullable|numeric|min:0',
            'maksimal_diskon' => 'nullable|numeric|min:0',
            'mulai_pada' => 'required|date',
            'berakhir_pada' => 'required|date|after:mulai_pada',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_promo.required' => 'Nama promo wajib diisi.',
            'kode_promo.required' => 'Kode promo wajib diisi.',
            'kode_promo.unique' => 'Kode promo sudah digunakan.',
            'tipe_diskon.required' => 'Tipe diskon wajib dipilih.',
            'nilai_diskon.required' => 'Nilai diskon wajib diisi.',
            'mulai_pada.required' => 'Tanggal mulai wajib diisi.',
            'berakhir_pada.required' => 'Tanggal berakhir wajib diisi.',
            'berakhir_pada.after' => 'Tanggal berakhir harus setelah tanggal mulai.',
        ]);

        $promo = Promotion::create([
            'creator_id' => Auth::id(),
            'kode_promo' => $data['kode_promo'],
            'nama_promo' => $data['nama_promo'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'tipe_diskon' => $data['tipe_diskon'],
            'nilai_diskon' => $data['nilai_diskon'],
            'minimal_pembelian' => $data['minimal_pembelian'] ?? 0,
            'maksimal_diskon' => $data['maksimal_diskon'] ?? null,
            'mulai_pada' => $data['mulai_pada'],
            'berakhir_pada' => $data['berakhir_pada'],
            'status' => Promotion::STATUS_AKTIF,
        ]);

        ActivityLogger::log(
            'promo.create',
            Promotion::class,
            $promo->promotion_id,
            null,
            $promo->toArray(),
            'Membuat promo platform baru: '.$promo->nama_promo
        );

        return back()->with('toast', [
            'message' => 'Promo "'.$promo->nama_promo.'" berhasil dibuat.',
            'icon' => 'task_alt',
        ]);
    }

    public function getDetail(Promotion $promo)
    {
        $promo->load(['products.product', 'categories.category']);

        return response()->json($promo);
    }

    public function update(Request $request, Promotion $promo)
    {
        $data = $request->validate([
            'nama_promo' => 'required|string|max:150',
            'kode_promo' => 'required|string|max:100|unique:promotions,kode_promo,'.$promo->promotion_id.',promotion_id',
            'tipe_diskon' => 'required|in:persen,nominal',
            'nilai_diskon' => 'required|numeric|min:0',
            'minimal_pembelian' => 'nullable|numeric|min:0',
            'maksimal_diskon' => 'nullable|numeric|min:0',
            'mulai_pada' => 'required|date',
            'berakhir_pada' => 'required|date|after:mulai_pada',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $lama = $promo->toArray();

        $promo->update([
            'nama_promo' => $data['nama_promo'],
            'kode_promo' => $data['kode_promo'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'tipe_diskon' => $data['tipe_diskon'],
            'nilai_diskon' => $data['nilai_diskon'],
            'minimal_pembelian' => $data['minimal_pembelian'] ?? 0,
            'maksimal_diskon' => $data['maksimal_diskon'] ?? null,
            'mulai_pada' => $data['mulai_pada'],
            'berakhir_pada' => $data['berakhir_pada'],
            'status' => $data['status'],
        ]);

        ActivityLogger::log(
            'promo.update',
            Promotion::class,
            $promo->promotion_id,
            $lama,
            $promo->fresh()->toArray(),
            'Memperbarui promo: '.$promo->nama_promo
        );

        return back()->with('toast', [
            'message' => 'Promo "'.$promo->nama_promo.'" berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function destroy(Promotion $promo)
    {
        $nama = $promo->nama_promo;

        ActivityLogger::log(
            'promo.delete',
            Promotion::class,
            $promo->promotion_id,
            $promo->toArray(),
            null,
            'Menghapus promo: '.$nama
        );

        $promo->delete();

        return back()->with('toast', [
            'message' => 'Promo "'.$nama.'" berhasil dihapus.',
            'icon' => 'task_alt',
        ]);
    }
}
