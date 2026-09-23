<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Models\Notification;
use App\Models\User;
use App\Support\AdminContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomplainController extends Controller
{
    public function index()
    {
        $storeIds = AdminContext::assignedStoreIds();

        $complaints = Complaint::with(['user', 'order.store', 'orderItem.productVariant.product', 'store', 'messages.sender'])
            ->whereHas('order', fn($q) => $q->whereIn('store_id', $storeIds))
            ->orderByDesc('dibuat_pada')
            ->get();

        $terbuka = $complaints
            ->whereIn('status', [Complaint::STATUS_OPEN, Complaint::STATUS_DIPROSES])
            ->count();
        $menunggu = $complaints
            ->where('status', Complaint::STATUS_OPEN)
            ->count();
        $selesai = $complaints
            ->where('status', Complaint::STATUS_SELESAI)
            ->count();
        $selesaiBulanIni = $complaints
            ->where('status', Complaint::STATUS_SELESAI)
            ->filter(fn($c) => $c->dibuat_pada && $c->dibuat_pada->month === now()->month)
            ->count();
        $resolution = $complaints->count() > 0 ? round($selesai / $complaints->count() * 100) : 0;

        return view('Admin.komplain.index', compact(
            'complaints', 'terbuka', 'menunggu', 'selesaiBulanIni', 'resolution'
        ));
    }

    /**
     * Thread percakapan (JSON) — diambil AJAX oleh drawer chat.
     */
    public function messages(Complaint $komplain)
    {
        abort_unless($this->belongsToStore($komplain), 404);

        $messages = $komplain->messages()
            ->withTrashed()
            ->with('sender.role')
            ->orderBy('created_at')
            ->get()
            ->reject(fn ($message) => $message->deletedFor(Auth::id()))
            ->map(fn ($message) => $message->toChatArray(Auth::id()))
            ->values();

        return response()->json($messages);
    }

    /**
     * Balas dalam thread komplain (AJAX / drawer chat).
     */
    public function storeMessage(Request $request, Complaint $komplain)
    {
        abort_unless($this->belongsToStore($komplain), 404);

        if (in_array($komplain->status, [Complaint::STATUS_SELESAI, Complaint::STATUS_DITUTUP], true)) {
            return response()->json(['message' => 'Komplain ini sudah selesai.'], 422);
        }

        $data = $request->validate([
            'pesan' => 'required|string|min:3|max:2000',
        ], [
            'pesan.required' => 'Pesan wajib diisi.',
            'pesan.min' => 'Pesan minimal 3 karakter.',
            'pesan.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        $pesan = ComplaintMessage::create([
            'complaint_id' => $komplain->complaint_id,
            'sender_id' => Auth::id(),
            'pesan' => $data['pesan'],
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
                'judul' => 'Balasan Komplain',
                'pesan' => sprintf('Toko membalas komplain #%s.', $komplain->kode ?? $komplain->complaint_id),
                'url' => route('customer.order-tracking'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Komplain Dibalas', sprintf('Balasan komplain #%s terkirim ke customer.', $komplain->kode ?? $komplain->complaint_id), route('admin.komplain', ['open' => $komplain->complaint_id]));

        return response()->json($pesan->load('sender'), 201);
    }

    /**
     * Ubah isi pesan milik sendiri (maksimal 10 menit setelah dikirim).
     */
    public function updateMessage(Request $request, Complaint $komplain, ComplaintMessage $message)
    {
        abort_unless($this->belongsToStore($komplain), 404);

        if ($message->complaint_id !== $komplain->complaint_id) {
            abort(404);
        }

        if ($message->deleted_at) {
            return response()->json(['message' => 'Pesan sudah dihapus.'], 422);
        }

        if (in_array($komplain->status, [Complaint::STATUS_SELESAI, Complaint::STATUS_DITUTUP], true)) {
            return response()->json(['message' => 'Komplain ini sudah selesai dan tidak dapat diubah.'], 422);
        }

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Hanya pemilik pesan yang dapat mengedit.'], 403);
        }

        if ($message->created_at->lt(now()->subMinutes(10))) {
            return response()->json(['message' => 'Pesan hanya dapat diedit dalam 10 menit pertama setelah dikirim.'], 422);
        }

        $data = $request->validate([
            'pesan' => 'required|string|min:3|max:2000',
        ], [
            'pesan.required' => 'Pesan wajib diisi.',
            'pesan.min' => 'Pesan minimal 3 karakter.',
            'pesan.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        $message->update([
            'pesan' => $data['pesan'],
            'edited_at' => now(),
        ]);

        return response()->json($message->toChatArray(Auth::id()));
    }

    /**
     * Hapus pesan — per=all (untuk semua) hanya untuk pesan milik sendiri,
     * per=me (hanya untuk saya) boleh untuk pesan siapa pun di thread.
     */
    public function destroyMessage(Request $request, Complaint $komplain, ComplaintMessage $message)
    {
        abort_unless($this->belongsToStore($komplain), 404);

        if ($message->complaint_id !== $komplain->complaint_id) {
            abort(404);
        }

        if (in_array($komplain->status, [Complaint::STATUS_SELESAI, Complaint::STATUS_DITUTUP], true)) {
            return response()->json(['message' => 'Komplain ini sudah selesai dan tidak dapat diubah.'], 422);
        }

        $per = $request->input('per', 'me');

        if ($per === 'me') {
            $deletedBy = $message->deleted_by ?? [];
            if (!in_array(Auth::id(), $deletedBy, true)) {
                $deletedBy[] = Auth::id();
                $message->update(['deleted_by' => $deletedBy]);
            }

            return response()->json([
                'deleted' => true,
                'complaint_message_id' => $message->complaint_message_id,
            ]);
        }

        if ($message->deleted_at) {
            return response()->json(['message' => 'Pesan sudah dihapus.'], 422);
        }

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Hanya pemilik pesan yang dapat menghapus untuk semua orang.'], 403);
        }

        if ($message->created_at->lt(now()->subDays(1))) {
            return response()->json(['message' => 'Pesan hanya dapat dihapus untuk semua orang dalam 1 hari setelah dikirim.'], 422);
        }

        $message->delete();

        return response()->json([
            'deleted' => true,
            'complaint_message_id' => $message->complaint_message_id,
        ]);
    }

    public function eskalasi(Request $request, Complaint $komplain): \Illuminate\Http\RedirectResponse
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
                'url' => route('owner.komplain', ['open' => $komplain->complaint_id]),
            ]);
        }

        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Komplain Dieskalasi', sprintf('Komplain #%d dieskalasi ke Owner.', $komplain->complaint_id), route('admin.komplain'));

        return back()->with('success', 'Komplain dieskalasi ke Owner Toko.');
    }

    protected function belongsToStore(Complaint $complaint): bool
    {
        return in_array($complaint->order?->store_id, AdminContext::assignedStoreIds());
    }
}