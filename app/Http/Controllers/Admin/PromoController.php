<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Promotion;
use App\Models\Store;
use App\Support\AdminContext;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $store = Store::whereIn('store_id', $storeIds)->first();

        $promos = Promotion::whereIn('store_id', $storeIds)
            ->when($request->input('status'), fn ($q, $s) => $q->where('status', $s))
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        $counts = [
            'aktif' => Promotion::whereIn('store_id', $storeIds)->where('status', 'aktif')->count(),
            'terjadwal' => Promotion::whereIn('store_id', $storeIds)->where('mulai_pada', '>', now())->count(),
            'total' => Promotion::whereIn('store_id', $storeIds)->count(),
        ];

        return view('Admin.promo.index', compact('promos', 'counts', 'store'));
    }

    public function store(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $storeId = $storeIds[0] ?? null;

        if (! $storeId) {
            return back()->with('error', 'Admin belum ditugaskan ke toko mana pun.');
        }

        $validated = $request->validate([
            'kode_promo' => ['required', 'string', 'max:30', 'unique:promotions,kode_promo'],
            'nama_promo' => ['required', 'string', 'max:100'],
            'tipe_diskon' => ['required', 'in:persen,nominal'],
            'nilai_diskon' => ['required', 'numeric', 'min:1'],
            'minimal_pembelian' => ['nullable', 'numeric', 'min:0'],
            'maksimal_diskon' => ['nullable', 'numeric', 'min:0'],
            'mulai_pada' => ['required', 'date'],
            'berakhir_pada' => ['required', 'date', 'after:mulai_pada'],
        ]);

        Promotion::create([
            'creator_id' => $request->user()->user_id,
            'store_id' => $storeId,
            'kode_promo' => strtoupper($validated['kode_promo']),
            'nama_promo' => $validated['nama_promo'],
            'tipe_diskon' => $validated['tipe_diskon'],
            'nilai_diskon' => $validated['nilai_diskon'],
            'minimal_pembelian' => $validated['minimal_pembelian'] ?? 0,
            'maksimal_diskon' => $validated['maksimal_diskon'] ?? null,
            'mulai_pada' => $validated['mulai_pada'],
            'berakhir_pada' => $validated['berakhir_pada'],
            'status' => 'aktif',
        ]);

        Notification::fireSelf(Notification::TIPE_PROMO, 'Promo Ditambahkan', sprintf('Promo "%s" (kode %s) berhasil ditambahkan.', $validated['nama_promo'], strtoupper($validated['kode_promo'])), route('admin.promo'));

        return redirect()->route('admin.promo')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function update(Request $request, Promotion $promo)
    {
        $storeIds = AdminContext::assignedStoreIds();
        if (! in_array($promo->store_id, $storeIds, true)) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_promo' => ['required', 'string', 'max:100'],
            'tipe_diskon' => ['required', 'in:persen,nominal'],
            'nilai_diskon' => ['required', 'numeric', 'min:1'],
            'minimal_pembelian' => ['nullable', 'numeric', 'min:0'],
            'maksimal_diskon' => ['nullable', 'numeric', 'min:0'],
            'mulai_pada' => ['required', 'date'],
            'berakhir_pada' => ['required', 'date', 'after:mulai_pada'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $promo->update([
            'nama_promo' => $validated['nama_promo'],
            'tipe_diskon' => $validated['tipe_diskon'],
            'nilai_diskon' => $validated['nilai_diskon'],
            'minimal_pembelian' => $validated['minimal_pembelian'] ?? 0,
            'maksimal_diskon' => $validated['maksimal_diskon'] ?? null,
            'mulai_pada' => $validated['mulai_pada'],
            'berakhir_pada' => $validated['berakhir_pada'],
            'status' => $validated['status'],
        ]);

        Notification::fireSelf(Notification::TIPE_PROMO, 'Promo Diperbarui', sprintf('Promo "%s" berhasil diperbarui.', $promo->nama_promo), route('admin.promo'));

        return back()->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promotion $promo)
    {
        $storeIds = AdminContext::assignedStoreIds();
        if (! in_array($promo->store_id, $storeIds, true)) {
            abort(403);
        }
        $promo->delete();

        Notification::fireSelf(Notification::TIPE_PROMO, 'Promo Dihapus', sprintf('Promo "%s" telah dihapus.', $promo->nama_promo), route('admin.promo'));

        return back()->with('success', 'Promo berhasil dihapus.');
    }

    public function toggle(Promotion $promo)
    {
        $storeIds = AdminContext::assignedStoreIds();
        if (! in_array($promo->store_id, $storeIds, true)) {
            abort(403);
        }
        $promo->update(['status' => $promo->status === 'aktif' ? 'nonaktif' : 'aktif']);

        Notification::fireSelf(Notification::TIPE_PROMO, 'Status Promo Diubah', sprintf('Promo "%s" kini %s.', $promo->nama_promo, $promo->status), route('admin.promo'));

        return back()->with('success', 'Status promo diubah menjadi '.ucfirst($promo->status).'.');
    }
}