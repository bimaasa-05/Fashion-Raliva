<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ProductSlotPackage;
use App\Models\StoreSlotSubscription;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class PaketSlotController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $subs = StoreSlotSubscription::where('store_id', $storeId)->first();
        $total = $subs?->jumlah_slot ?? 0;
        $used = $subs?->slot_terpakai ?? 0;
        $sisa = max(0, $total - $used);
        $progress = $total > 0 ? (int) round($used / $total * 100) : 0;

        $active = [
            'nama' => 'Growth',
            'harga' => 'Rp 199.000',
            'total' => $total,
            'used' => $used,
            'sisa' => $sisa,
            'progress' => $progress,
        ];

        $packages = ProductSlotPackage::orderBy('jumlah_slot')->get();

        return view('Owner.paket-slot.index', compact('active', 'packages'));
    }
}
