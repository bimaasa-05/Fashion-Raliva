<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianDanaController extends Controller
{
    public function index(Request $request)
    {
        $storeId = \App\Support\OwnerContext::firstStoreId();

        $tab = $request->query('tab', 'pengajuan');

        $base = Refund::query()
            ->with(['order', 'requester', 'reviewer', 'items']);

        if ($storeId) {
            $base->whereHas('order', fn ($q) => $q->where('store_id', $storeId));
        }

        $stats = [
            'pengajuan' => (clone $base)->whereIn('status', [Refund::STATUS_REQUESTED, Refund::STATUS_ESKALASI])->count(),
            'disetujui' => (clone $base)->where('status', Refund::STATUS_DISETUJUI)->count(),
            'ditolak' => (clone $base)->where('status', Refund::STATUS_DITOLAK)->count(),
            'selesai' => (clone $base)->where('status', Refund::STATUS_SELESAI)->count(),
        ];

        $refunds = match ($tab) {
            'disetujui' => (clone $base)->where('status', Refund::STATUS_DISETUJUI)->orderByDesc('diajukan_pada')->get(),
            'ditolak' => (clone $base)->where('status', Refund::STATUS_DITOLAK)->orderByDesc('diajukan_pada')->get(),
            'selesai' => (clone $base)->where('status', Refund::STATUS_SELESAI)->orderByDesc('diajukan_pada')->get(),
            default => (clone $base)->whereIn('status', [Refund::STATUS_REQUESTED, Refund::STATUS_ESKALASI])->orderByDesc('diajukan_pada')->get(),
        };

        return view('Owner.pengembalian-dana.index', [
            'stats' => $stats,
            'refunds' => $refunds,
            'activeTab' => in_array($tab, ['pengajuan', 'disetujui', 'ditolak', 'selesai'], true) ? $tab : 'pengajuan',
        ]);
    }

    public function setujui(Request $request, Refund $refund): \Illuminate\Http\RedirectResponse
    {
        $this->assertStoreOwnerScope($refund);

        if (! in_array($refund->status, [Refund::STATUS_REQUESTED, Refund::STATUS_ESKALASI], true)) {
            return back()->with('error', 'Refund sudah diproses.');
        }

        $refund->update([
            'status' => Refund::STATUS_DISETUJUI,
            'reviewed_by' => Auth::id(),
            'selesai_pada' => now(),
        ]);

        return back()->with('success', 'Refund ' . $refund->kode . ' disetujui.');
    }

    public function tolak(Request $request, Refund $refund): \Illuminate\Http\RedirectResponse
    {
        $this->assertStoreOwnerScope($refund);

        if (! in_array($refund->status, [Refund::STATUS_REQUESTED, Refund::STATUS_ESKALASI], true)) {
            return back()->with('error', 'Refund sudah diproses.');
        }

        $data = $request->validate([
            'alasan_penolakan' => 'nullable|string|max:1000',
        ]);

        $refund->update([
            'status' => Refund::STATUS_DITOLAK,
            'reviewed_by' => Auth::id(),
            'alasan_penolakan' => $data['alasan_penolakan'] ?? null,
            'selesai_pada' => now(),
        ]);

        return back()->with('success', 'Refund ' . $refund->kode . ' ditolak.');
    }

    public function selesaikan(Request $request, Refund $refund): \Illuminate\Http\RedirectResponse
    {
        $this->assertStoreOwnerScope($refund);

        if ($refund->status !== Refund::STATUS_DISETUJUI) {
            return back()->with('error', 'Hanya refund disetujui yang dapat diselesaikan.');
        }

        $refund->update([
            'status' => Refund::STATUS_SELESAI,
            'selesai_pada' => now(),
        ]);

        return back()->with('success', 'Refund ' . $refund->kode . ' ditandai selesai.');
    }

    private function assertStoreOwnerScope(Refund $refund): void
    {
        $storeId = \App\Support\OwnerContext::firstStoreId();

        if (! $storeId) {
            abort(403, 'Toko tidak ditemukan.');
        }

        $order = $refund->order()->select('store_id')->first();

        if (! $order || (int) $order->store_id !== (int) $storeId) {
            abort(403, 'Refund ini bukan untuk toko Anda.');
        }
    }
}
