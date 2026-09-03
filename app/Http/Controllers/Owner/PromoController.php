<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();
        $store = OwnerContext::currentStore();

        $promos = Promotion::where('store_id', $storeId)
            ->when($request->input('status'), fn($q, $s) => $q->where('status', $s))
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        $counts = [
            'aktif' => Promotion::where('store_id', $storeId)->where('status', 'aktif')->count(),
            'terjadwal' => Promotion::where('store_id', $storeId)->where('mulai_pada', '>', now())->count(),
            'total' => Promotion::where('store_id', $storeId)->count(),
        ];

        return view('Owner.promo.index', compact('promos', 'counts', 'store'));
    }

    public function store(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        if (! $storeId) {
            return back()->with('error', 'Anda belum memiliki toko. Ajukan toko terlebih dahulu sebelum membuat promo.');
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

        return redirect()->route('owner.promo')->with('success', 'Promo berhasil ditambahkan.');
    }

    public function update(Request $request, Promotion $promo)
    {
        $storeId = OwnerContext::firstStoreId();
        if ((int) $promo->store_id !== (int) $storeId) abort(403);

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

        return back()->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promotion $promo)
    {
        $storeId = OwnerContext::firstStoreId();
        if ((int) $promo->store_id !== (int) $storeId) abort(403);
        $promo->delete();
        return back()->with('success', 'Promo berhasil dihapus.');
    }

    public function toggle(Promotion $promo)
    {
        $storeId = OwnerContext::firstStoreId();
        if ((int) $promo->store_id !== (int) $storeId) abort(403);
        $promo->update(['status' => $promo->status === 'aktif' ? 'nonaktif' : 'aktif']);
        return back()->with('success', 'Status promo diubah menjadi '.ucfirst($promo->status).'.');
    }
}
