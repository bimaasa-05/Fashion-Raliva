<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $reviews = Review::with(['user', 'product'])
            ->where('store_id', $storeId)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $avg = (float) Review::where('store_id', $storeId)->avg('rating');
        $total = Review::where('store_id', $storeId)->count();

        $dist = Review::where('store_id', $storeId)
            ->selectRaw('FLOOR(rating) as bintang, COUNT(*) as jumlah')
            ->groupBy('bintang')
            ->pluck('jumlah', 'bintang')
            ->all();

        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $jumlah = (int) ($dist[$i] ?? 0);
            $percent = $total > 0 ? round($jumlah / $total * 100, 1) : 0;
            $distribution[] = ['label' => "Bintang $i", 'percent' => $percent, 'count' => $jumlah];
        }

        return view('Owner.ulasan.index', compact('reviews', 'avg', 'total', 'distribution'));
    }
}
