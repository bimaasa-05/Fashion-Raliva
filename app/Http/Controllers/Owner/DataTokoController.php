<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Support\OwnerContext;
use App\Models\Review;

class DataTokoController extends Controller
{
    public function index()
    {
        $store = OwnerContext::currentStore();
        $rating = $store ? (float) Review::where('store_id', $store->store_id)->avg('rating') : 0;
        $reviewCount = $store ? Review::where('store_id', $store->store_id)->count() : 0;

        return view('Owner.data-toko.index', compact('store', 'rating', 'reviewCount'));
    }
}
