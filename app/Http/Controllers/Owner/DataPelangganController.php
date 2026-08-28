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

        // segment + rank
        $ranked = collect($rows->items())->map(function ($c, $i) {
            $c->rank = $i + 1;
            $c->segment = $c->rank <= 4 ? 'leader' : ($c->jumlah_order >= 2 ? 'setia' : 'baru');
            $c->initials = collect(explode(' ', $c->name))->map(fn($w) => mb_substr($w, 0, 1))->slice(0, 2)->implode('');
            return $c;
        });
        $rows->setCollection($ranked);

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
