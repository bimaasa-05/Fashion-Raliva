<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Models\Notification;
use App\Models\User;
use App\Support\AdminContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomplainController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();
        $complaints = Complaint::with(['user', 'order', 'messages.sender'])
            ->whereHas('order', fn($q) => $q->whereIn('store_id', $storeIds))
            ->orderByRaw("CASE status WHEN 'open' THEN 0 WHEN 'diproses' THEN 1 WHEN 'escalated' THEN 2 ELSE 3 END")
            ->orderByDesc('dibuat_pada')
            ->paginate(12);

        return view('Admin.komplain.index', compact('complaints'));
    }

    public function balas(Request $request, Complaint $komplain): RedirectResponse
    {
        if (! in_array($komplain->order?->store_id, AdminContext::assignedStoreIds())) {
            abort(403);
        }

        $request->validate([
            'pesan' => 'required|string|min:3',
        ]);

        ComplaintMessage::create([
            'complaint_id' => $komplain->complaint_id,
            'sender_id' => Auth::id(),
            'pesan' => $request->input('pesan'),
            'lampiran' => null,
        ]);

        if ($komplain->status === Complaint::STATUS_OPEN) {
            $komplain->update(['status' => Complaint::STATUS_DIPROSES]);
        }

        if ($komplain->user_id) {
            Notification::create([
                'user_id' => $komplain->user_id,
                'aktor_id' => Auth::id(),
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Komplain Dibalas Admin',
                'pesan' => sprintf('Komplain #%s mendapat balasan dari toko.', $komplain->kode ?? $komplain->complaint_id),
                'url' => route('customer.order-tracking'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Komplain Dibalas', sprintf('Balasan komplain #%s terkirim ke customer.', $komplain->kode ?? $komplain->complaint_id), route('admin.komplain'));

        return back()->with('success', 'Balasan terkirim ke customer.');
    }

    public function eskalasi(Request $request, Complaint $komplain): RedirectResponse
    {
        if (! in_array($komplain->order?->store_id, AdminContext::assignedStoreIds())) {
            abort(403);
        }

        if ($komplain->status === Complaint::STATUS_SELESAI || $komplain->status === Complaint::STATUS_DITUTUP) {
            return back()->with('error', 'Komplain sudah ditutup.');
        }

        $komplain->update(['status' => Complaint::STATUS_ESKALASI]);

        // Beritahu Owner toko terkait (eskalasi butuh keputusan final Owner).
        $ownerId = $komplain->order?->store?->owner_id
            ?? User::whereHas('role', fn ($q) => $q->where('nama_role', 'Owner'))->first()?->user_id;
        if ($ownerId) {
            Notification::create([
                'user_id' => $ownerId,
                'aktor_id' => Auth::id(),
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Eskalasi Komplain',
                'pesan' => "Komplain #{$komplain->complaint_id} dieskalasi ke Anda untuk keputusan final.",
                'url' => route('owner.komplain.messages', $komplain->complaint_id),
            ]);
        }

        return back()->with('success', 'Komplain dieskalasi ke Owner Toko.');
    }
}
