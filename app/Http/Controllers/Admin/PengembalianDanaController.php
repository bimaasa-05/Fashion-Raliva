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
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Eskalasi Refund',
                'pesan' => "Refund {$refund->kode} dieskalasi ke Anda untuk keputusan final. Alasan: " . ($refund->alasan ?: '-'),
            ]);
        }

        return back()->with('success', 'Refund ' . $refund->kode . ' dieskalasi ke Owner Toko.');
    }
}
