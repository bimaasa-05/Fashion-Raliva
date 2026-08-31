<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataPelangganController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $rows = DB::table('users')
            ->join('checkouts', 'checkouts.user_id', '=', 'users.user_id')
            ->join('orders', 'orders.checkout_id', '=', 'checkouts.checkout_id')
            ->where('orders.store_id', $storeId)
            ->select('users.user_id as id', 'users.nama_lengkap as name', 'users.email', 'users.created_at as join_date')
            ->selectRaw('SUM(orders.grand_total) as total_belanja')
            ->selectRaw('COUNT(orders.order_id) as jumlah_order')
            ->selectRaw('MAX(orders.created_at) as last_order')
            ->groupBy('users.user_id', 'users.nama_lengkap', 'users.email', 'users.created_at')
            ->orderByDesc('total_belanja')
            ->paginate(10)
            ->withQueryString();

        $ranked = collect($rows->items())->map(function ($c, $i) {
            $c->rank = $i + 1;
            $c->segment = $c->rank <= 4 ? 'leader' : ($c->jumlah_order >= 2 ? 'setia' : 'baru');
            $c->initials = collect(explode(' ', $c->name))->map(fn($w) => mb_substr($w, 0, 1))->slice(0, 2)->implode('');
            return $c;
        });
        $rows->setCollection($ranked);

        // Barang dibeli per customer (halaman ini saja) dalam 1 query
        $userIds = $ranked->pluck('id')->all();
        $itemsByUser = DB::table('orders')
            ->join('checkouts', 'checkouts.checkout_id', '=', 'orders.checkout_id')
            ->join('order_items', 'order_items.order_id', '=', 'orders.order_id')
            ->join('product_variants', 'product_variants.product_variant_id', '=', 'order_items.product_variant_id')
            ->join('products', 'products.product_id', '=', 'product_variants.product_id')
            ->where('orders.store_id', $storeId)
            ->whereIn('checkouts.user_id', $userIds)
            ->select('checkouts.user_id', 'products.nama_produk', DB::raw('SUM(order_items.quantity) as qty'))
            ->groupBy('checkouts.user_id', 'products.nama_produk')
            ->get()
            ->groupBy('user_id')
            ->map(function ($grp) {
                return $grp->sortByDesc('qty')->take(3)->map(fn($i) => $i->nama_produk . ' (' . $i->qty . ')');
            });

        $ranked->each(function ($c) use ($itemsByUser) {
            $c->items = $itemsByUser->get($c->id, collect());
        });

        // Riwayat pesanan per customer (untuk modal histori)
        $ordersByUser = DB::table('orders')
            ->join('checkouts', 'checkouts.checkout_id', '=', 'orders.checkout_id')
            ->join('order_items', 'order_items.order_id', '=', 'orders.order_id')
            ->join('product_variants', 'product_variants.product_variant_id', '=', 'order_items.product_variant_id')
            ->join('products', 'products.product_id', '=', 'product_variants.product_id')
            ->where('orders.store_id', $storeId)
            ->whereIn('checkouts.user_id', $userIds)
            ->select('checkouts.user_id', 'orders.order_id', 'orders.nomor_order', 'orders.status', 'orders.grand_total', 'orders.created_at', 'products.nama_produk', 'order_items.quantity')
            ->orderByDesc('orders.created_at')
            ->get()
            ->groupBy('user_id')
            ->map(function ($grp) {
                return $grp->groupBy('order_id')->map(function ($lines) {
                    return (object) [
                        'order_id' => $lines->first()->order_id,
                        'nomor_order' => $lines->first()->nomor_order,
                        'status' => $lines->first()->status,
                        'grand_total' => $lines->first()->grand_total,
                        'created_at' => $lines->first()->created_at,
                        'items' => $lines->map(fn($l) => $l->nama_produk . ' ×' . $l->quantity)->all(),
                    ];
                })->values();
            });

        $ranked->each(function ($c) use ($ordersByUser) {
            $c->ordersList = $ordersByUser->get($c->id, collect());
        });

        $topLeader = $ranked->first();
        $top3 = $ranked->take(3)->values();

        $summary = [
            'total' => $rows->total(),
            'baru' => DB::table('users')
                ->join('checkouts', 'checkouts.user_id', '=', 'users.user_id')
                ->join('orders', 'orders.checkout_id', '=', 'checkouts.checkout_id')
                ->where('orders.store_id', $storeId)
                ->whereMonth('users.created_at', now()->month)
                ->whereYear('users.created_at', now()->year)
                ->distinct('users.user_id')->count('users.user_id'),
            'repeat' => $ranked->where('jumlah_order', '>=', 2)->count(),
            'rata' => $ranked->avg('total_belanja') ?? 0,
        ];

        return view('Owner.data-pelanggan.index', compact('rows', 'topLeader', 'top3', 'summary'));
    }
}
