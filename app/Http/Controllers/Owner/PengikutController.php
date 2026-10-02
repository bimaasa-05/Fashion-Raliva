<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengikutController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();
        $cari = trim((string) $request->query('cari', ''));

        $query = DB::table('store_follows')
            ->join('users', 'users.user_id', '=', 'store_follows.user_id')
            ->where('store_follows.store_id', $storeId)
            ->select(
                'users.user_id as id',
                'users.nama_lengkap as name',
                'users.email',
                'store_follows.created_at as follow_date'
            );

        if ($cari !== '') {
            $like = '%' . $cari . '%';
            $query->where(function ($w) use ($like) {
                $w->where('users.nama_lengkap', 'like', $like)
                    ->orWhere('users.email', 'like', $like);
            });
        }

        $rows = $query->orderByDesc('store_follows.created_at')
            ->paginate(12)
            ->withQueryString();

        $userIds = collect($rows->items())->pluck('id')->all();

        $belanja = DB::table('orders')
            ->join('checkouts', 'checkouts.checkout_id', '=', 'orders.checkout_id')
            ->where('orders.store_id', $storeId)
            ->whereIn('checkouts.user_id', $userIds)
            ->select(
                'checkouts.user_id',
                DB::raw('COUNT(orders.order_id) as jumlah_order'),
                DB::raw('SUM(orders.grand_total) as total_belanja'),
                DB::raw('MAX(orders.created_at) as last_order')
            )
            ->groupBy('checkouts.user_id')
            ->get()
            ->keyBy('user_id');

        $ranked = collect($rows->items())->map(function ($c) use ($belanja) {
            $b = $belanja->get($c->id);
            $c->jumlah_order = (int) ($b->jumlah_order ?? 0);
            $c->total_belanja = (float) ($b->total_belanja ?? 0);
            $c->last_order = $b->last_order ?? null;
            $c->is_customer = $c->jumlah_order > 0;
            $c->initials = collect(explode(' ', (string) $c->name))
                ->map(fn ($w) => mb_substr($w, 0, 1))
                ->slice(0, 2)
                ->implode('');
            return $c;
        });
        $rows->setCollection($ranked);

        $summary = [
            'total' => DB::table('store_follows')->where('store_id', $storeId)->count(),
            'baru' => DB::table('store_follows')
                ->where('store_id', $storeId)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'belanja' => DB::table('store_follows')
                ->join('checkouts', 'checkouts.user_id', '=', 'store_follows.user_id')
                ->join('orders', 'orders.checkout_id', '=', 'checkouts.checkout_id')
                ->where('store_follows.store_id', $storeId)
                ->where('orders.store_id', $storeId)
                ->distinct()
                ->count('store_follows.user_id'),
        ];

        return view('Owner.pengikut.index', compact('rows', 'summary', 'cari'));
    }
}
