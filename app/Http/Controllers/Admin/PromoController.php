<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promotion::orderByDesc('promotion_id')->paginate(12);

        return view('Admin.promo.index', compact('promos'));
    }

    public function toggle(Request $request, Promotion $promotion): RedirectResponse
    {
        $promotion->update([
            'status' => $promotion->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        Notification::fireSelf(Notification::TIPE_PROMO, 'Status Promo Diperbarui', sprintf('Promo "%s" kini %s.', $promotion->nama_promo ?? ('#' . $promotion->promotion_id), $promotion->status), route('admin.promo'));

        return back()->with('success', 'Status promo diperbarui.');
    }
}
