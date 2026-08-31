<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $query = Product::with(['category', 'variants'])
            ->where('store_id', $storeId);

        if ($kat = $request->input('kategori')) {
            $katLabel = \Illuminate\Support\Str::title($kat);
            $query->whereHas('category', fn($q) => $q->where('nama_kategori', $katLabel));
        }
        if ($status = $request->input('status-produk')) {
            if (in_array($status, ['aktif', 'nonaktif'])) {
                $query->where('status', $status);
            }
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        // terjual per produk (dari order item via variant)
        $ids = $products->pluck('product_id')->all();
        $sold = OrderItem::query()
            ->select('product_variants.product_id', DB::raw('SUM(order_items.quantity) as total'))
            ->join('product_variants', 'product_variants.product_variant_id', '=', 'order_items.product_variant_id')
            ->whereIn('product_variants.product_id', $ids)
            ->groupBy('product_variants.product_id')
            ->pluck('total', 'product_variants.product_id');
        $products->getCollection()->transform(function ($p) use ($sold) {
            $p->terjual = (int) ($sold->get($p->product_id) ?? 0);
            return $p;
        });

        $slotAgg = \App\Models\StoreSlotSubscription::where('store_id', $storeId)
            ->where('status', 'aktif')
            ->selectRaw('COALESCE(SUM(jumlah_slot),0) as total, COALESCE(SUM(slot_terpakai),0) as used')
            ->first();
        $totalSlot = (int) ($slotAgg->total ?? 0);
        $usedSlot = (int) ($slotAgg->used ?? 0);

        $counts = [
            'total' => $usedSlot,
            'aktif' => Product::where('store_id', $storeId)->where('status', 'aktif')->count(),
            'nonaktif' => Product::where('store_id', $storeId)->where('status', 'nonaktif')->count(),
            'varian' => \App\Models\ProductVariant::whereHas('product', fn($q) => $q->where('store_id', $storeId))->count(),
        ];

        return view('Owner.produk.index', compact('products', 'counts', 'totalSlot', 'usedSlot'));
    }
}
