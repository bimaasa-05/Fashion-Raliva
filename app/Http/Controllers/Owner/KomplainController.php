<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class KomplainController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $complaints = Complaint::with('user')
            ->where('store_id', $storeId)
            ->orderByDesc('dibuat_pada')
            ->paginate(10)
            ->withQueryString();

        $all = Complaint::where('store_id', $storeId)->get();
        $terbuka = $all->whereIn('status', ['baru', 'proses'])->count();
        $menunggu = $all->where('status', 'baru')->count();
        $selesai = $all->where('status', 'selesai')->count();
        $selesaiBulanIni = $all->where('status', 'selesai')
            ->filter(fn($c) => $c->dibuat_pada && $c->dibuat_pada->month === now()->month)
            ->count();
        $resolution = $all->count() > 0 ? round($selesai / $all->count() * 100) : 0;

        return view('Owner.komplain.index', compact(
            'complaints', 'terbuka', 'menunggu', 'selesaiBulanIni', 'resolution'
        ));
    }
}
