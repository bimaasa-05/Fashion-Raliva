<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Refund;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianDanaController extends Controller
{
    public function index()
    {
        $pengajuan = Refund::with(['order', 'requester', 'items'])
            ->whereIn('status', [Refund::STATUS_REQUESTED, Refund::STATUS_ESKALASI])
            ->orderByDesc('diajukan_pada')
            ->get();

        $riwayat = Refund::with(['order', 'requester', 'reviewer'])
            ->whereNotIn('status', [Refund::STATUS_REQUESTED, Refund::STATUS_ESKALASI])
            ->orderByDesc('diajukan_pada')
            ->paginate(15);

        return view('Admin.pengembalian-dana.index', compact('pengajuan', 'riwayat'));
    }

    public function setujui(Request $request, Refund $refund): RedirectResponse
    {
        if ($refund->status !== Refund::STATUS_REQUESTED && $refund->status !== Refund::STATUS_ESKALASI) {
            return back()->with('error', 'Refund sudah diproses.');
        }

        $refund->update([
            'status' => Refund::STATUS_DISETUJUI,
            'reviewed_by' => Auth::id(),
            'selesai_pada' => now(),
        ]);

        if ($refund->requested_by) {
            Notification::create([
                'user_id' => $refund->requested_by,
                'aktor_id' => Auth::id(),
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Refund Disetujui',
                'pesan' => sprintf('Pengajuan refund %s disetujui: dana dikembalikan ke saldo Anda.', $refund->kode),
                'url' => route('customer.order-tracking'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Refund Disetujui', sprintf('Refund %s telah disetujui.', $refund->kode), route('admin.pengembalian-dana'));

        return back()->with('success', 'Refund ' . $refund->refund_id . ' disetujui.');
    }

    public function tolak(Request $request, Refund $refund): RedirectResponse
    {
        if ($refund->status !== Refund::STATUS_REQUESTED && $refund->status !== Refund::STATUS_ESKALASI) {
            return back()->with('error', 'Refund sudah diproses.');
        }

        $refund->update([
            'status' => Refund::STATUS_DITOLAK,
            'reviewed_by' => Auth::id(),
            'alasan_penolakan' => $request->input('alasan_penolakan'),
            'selesai_pada' => now(),
        ]);

        if ($refund->requested_by) {
            Notification::create([
                'user_id' => $refund->requested_by,
                'aktor_id' => Auth::id(),
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Refund Ditolak',
                'pesan' => sprintf('Pengajuan refund %s ditolak. Alasan: %s', $refund->kode, $request->input('alasan_penolakan') ?: '-'),
                'url' => route('customer.order-tracking'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Refund Ditolak', sprintf('Refund %s telah ditolak.', $refund->kode), route('admin.pengembalian-dana'));

        return back()->with('success', 'Refund ' . $refund->refund_id . ' ditolak.');
    }

    public function eskalasi(Request $request, Refund $refund): RedirectResponse
    {
        if ($refund->status !== Refund::STATUS_REQUESTED) {
            return back()->with('error', 'Refund tidak dapat dieskalasi.');
        }

        $refund->update([
            'status' => Refund::STATUS_ESKALASI,
            'reviewed_by' => Auth::id(),
        ]);

        // Beritahu Owner toko terkait (eskalasi butuh keputusan final Owner).
        $ownerId = $refund->order?->store?->owner_id;
        if ($ownerId) {
            Notification::create([
                'user_id' => $ownerId,
                'aktor_id' => Auth::id(),
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Eskalasi Refund',
                'pesan' => "Refund {$refund->kode} dieskalasi ke Anda untuk keputusan final. Alasan: " . ($refund->alasan ?: '-'),
                'url' => route('owner.pengembalian-dana'),
            ]);
        }

        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Refund Dieskalasi', sprintf('Refund %s dieskalasi ke Owner.', $refund->kode), route('admin.pengembalian-dana'));

        return back()->with('success', 'Refund ' . $refund->kode . ' dieskalasi ke Owner Toko.');
    }
}
