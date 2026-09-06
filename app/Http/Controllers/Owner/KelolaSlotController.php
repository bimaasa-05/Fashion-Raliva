<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\StoreSlotSubscription;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class KelolaSlotController extends Controller
{
    public function index()
    {
        $storeId = OwnerContext::firstStoreId();
        $store = OwnerContext::currentStore();
        $agg = StoreSlotSubscription::where('store_id', $storeId)->where('status', 'aktif')
            ->selectRaw('COALESCE(SUM(jumlah_slot),0) as total, COALESCE(SUM(slot_terpakai),0) as used')->first();
        $total = (int) ($agg->total ?? 0);
        $used = (int) ($agg->used ?? 0);
        $sisa = max(0, $total - $used);
        $pct = $total > 0 ? round($used / $total * 100) : 0;
        $riwayat = StoreSlotSubscription::where('store_id', $storeId)->orderByDesc('created_at')->limit(10)->get();

        return view('Owner.kelola-slot.index', compact('store', 'total', 'used', 'sisa', 'pct', 'riwayat'));
    }

    public function store(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();
        if (! $storeId) return back()->with('error', 'Anda belum memiliki toko.');
        $data = $request->validate([
            'jumlah_slot' => ['required', 'integer', 'min:10', 'max:500'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);
        StoreSlotSubscription::create([
            'store_id' => $storeId,
            'jumlah_slot' => $data['jumlah_slot'],
            'slot_terpakai' => 0,
            'status' => 'pending',
            'catatan' => $data['catatan'] ?? null,
        ]);

        return back()->with('success', 'Permintaan tambah '.$data['jumlah_slot'].' slot diajukan ke SuperAdmin.');
    }
}
