<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomplainController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['user', 'order', 'messages.sender'])
            ->orderByRaw("CASE status WHEN 'open' THEN 0 WHEN 'diproses' THEN 1 WHEN 'escalated' THEN 2 ELSE 3 END")
            ->orderByDesc('dibuat_pada')
            ->paginate(12);

        return view('Admin.komplain.index', compact('complaints'));
    }

    public function balas(Request $request, Complaint $complaint): RedirectResponse
    {
        $request->validate([
            'pesan' => 'required|string|min:3',
        ]);

        ComplaintMessage::create([
            'complaint_id' => $complaint->complaint_id,
            'sender_id' => Auth::id(),
            'pesan' => $request->input('pesan'),
            'lampiran' => null,
        ]);

        if ($complaint->status === Complaint::STATUS_OPEN) {
            $complaint->update(['status' => Complaint::STATUS_DIPROSES]);
        }

        return back()->with('success', 'Balasan terkirim ke customer.');
    }

    public function eskalasi(Request $request, Complaint $complaint): RedirectResponse
    {
        if ($complaint->status === Complaint::STATUS_SELESAI || $complaint->status === Complaint::STATUS_DITUTUP) {
            return back()->with('error', 'Komplain sudah ditutup.');
        }

        $complaint->update(['status' => Complaint::STATUS_ESKALASI]);

        // Beritahu Owner toko terkait (eskalasi butuh keputusan final Owner).
        $ownerId = $complaint->order?->store?->owner_id
            ?? User::whereHas('role', fn ($q) => $q->where('nama_role', 'Owner'))->first()?->user_id;
        if ($ownerId) {
            Notification::create([
                'user_id' => $ownerId,
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Eskalasi Komplain',
                'pesan' => "Komplain #{$complaint->complaint_id} dieskalasi ke Anda untuk keputusan final.",
            ]);
        }

        return back()->with('success', 'Komplain dieskalasi ke Owner Toko.');
    }
}
