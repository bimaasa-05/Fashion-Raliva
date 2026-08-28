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

        return view('Owner.promo.index', compact('promos', 'counts'));
    }
}
