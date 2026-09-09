<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Models\Notification;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomplainController extends Controller
{
    public function index(Request $request)
    {
        $complaints = Complaint::query()
            ->with(['user:user_id,nama_lengkap', 'store:store_id,nama_toko,owner_id'])
            ->orderByRaw("CASE status WHEN 'open' THEN 0 WHEN 'diproses' THEN 1 ELSE 2 END")
            ->orderByDesc('dibuat_pada')
            ->get()
            ->map(function (Complaint $complaint) {
                $complaint->eskalasi_oleh_sa = $complaint->messages()
                    ->where('sender_id', ActivityLogger::resolveActorId())
                    ->exists();

                return $complaint;
            });

        $stats = [
            'semua' => $complaints->count(),
            'open' => $complaints->where('status', Complaint::STATUS_OPEN)->count(),
            'diproses' => $complaints->where('status', Complaint::STATUS_DIPROSES)->count(),
            'selesai' => $complaints->where('status', Complaint::STATUS_SELESAI)->count(),
            'ditutup' => $complaints->where('status', Complaint::STATUS_DITUTUP)->count(),
        ];

        return view('SuperAdmin.komplain.index', [
            'complaints' => $complaints,
            'stats' => $stats,
        ]);
    }

    public function messages(Complaint $komplain)
    {
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

    public function destroyMessage(Request $request, Complaint $komplain, ComplaintMessage $message)
    {
        if ($message->complaint_id !== $komplain->complaint_id) {
            abort(404);
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

        if ($message->created_at->lt(now()->subDays(2))) {
            return response()->json(['message' => 'Pesan hanya dapat dihapus untuk semua orang dalam 2 hari setelah dikirim.'], 422);
        }

        $message->delete();

        return response()->json([
            'deleted' => true,
            'complaint_message_id' => $message->complaint_message_id,
        ]);
    }

    public function storeMessage(Request $request, Complaint $komplain)
    {
        $data = $request->validate([
            'pesan' => 'required|string|max:2000',
        ]);

        $pesan = ComplaintMessage::create([
            'complaint_id' => $komplain->complaint_id,
            'sender_id' => ActivityLogger::resolveActorId(),
            'pesan' => $data['pesan'],
        ]);

        Notification::create([
            'user_id' => $komplain->user_id,
            'aktor_id' => ActivityLogger::resolveActorId(),
            'tipe' => Notification::TIPE_KOMPLAIN,
            'judul' => 'Balasan Platform',
            'pesan' => sprintf('Platform memberikan balasan pada komplain "%s".', $komplain->subjek),
            'url' => route('customer.order-tracking'),
        ]);

        if ($komplain->store?->owner_id) {
            Notification::create([
                'user_id' => $komplain->store->owner_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Balasan Platform (Komplain)',
                'pesan' => sprintf('Platform membalas komplain "%s".', $komplain->subjek),
                'url' => route('owner.ulasan'),
            ]);
        }

        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Balasan Terkirim', sprintf('Balasan pada komplain "%s" terkirim.', $komplain->subjek), route('superadmin.komplain'));

        return response()->json($pesan->load('sender'));
    }

    public function eskalasi(Request $request, Complaint $komplain)
    {
        if (! in_array($komplain->status, [Complaint::STATUS_OPEN, Complaint::STATUS_DIPROSES], true)) {
            return back()->with('toast', [
                'message' => 'Hanya komplain berstatus terbuka atau diproses yang dapat dieskalasi.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $komplain->only(['status']);

        $komplain->update(['status' => Complaint::STATUS_DIPROSES]);

        $actorId = ActivityLogger::resolveActorId();

        ComplaintMessage::create([
            'complaint_id' => $komplain->complaint_id,
            'sender_id' => $actorId,
            'pesan' => 'Komplain ini dieskalasikan ke Owner toko untuk tindak lanjut segera. Mohon koordinasi internal dan berikan pembaruan kepada Customer.',
        ]);

        $ownerId = $komplain->store->owner_id ?? null;

        if ($ownerId) {
            Notification::create([
                'user_id' => $ownerId,
                'aktor_id' => $actorId,
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Komplain Dieskalasi',
                'pesan' => sprintf('Komplain "%s" dari Customer %s dieskalasikan oleh platform. Segera tangani dan perbarui statusnya.', $komplain->subjek, $komplain->user->nama_lengkap ?? '-'),
                'url' => route('owner.ulasan'),
            ]);
        }

        ActivityLogger::log(
            'complaint.escalate',
            Complaint::class,
            $komplain->complaint_id,
            $lama,
            ['status' => Complaint::STATUS_DIPROSES],
            sprintf('Mengeskalasi komplain "%s" ke Owner toko %s.', $komplain->subjek, $komplain->store->nama_toko ?? '-')
        );

        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Komplain Dieskalasi', sprintf('Komplain "%s" dieskalasi ke Owner.', $komplain->subjek), route('superadmin.komplain'));

        return back()->with('toast', [
            'message' => sprintf('Komplain %s dieskalasi ke Owner toko.', $komplain->subjek),
            'icon' => 'move_up',
        ]);
    }

    public function tutup(Request $request, Complaint $komplain)
    {
        if (! in_array($komplain->status, [Complaint::STATUS_OPEN, Complaint::STATUS_DIPROSES], true)) {
            return back()->with('toast', [
                'message' => 'Komplain ini sudah ditutup atau selesai.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        $lama = $komplain->only(['status']);

        $komplain->update([
            'status' => Complaint::STATUS_DITUTUP,
            'diselesaikan_pada' => now(),
        ]);

        if (! empty($data['catatan'])) {
            ComplaintMessage::create([
                'complaint_id' => $komplain->complaint_id,
                'sender_id' => ActivityLogger::resolveActorId(),
                'pesan' => '[Ditutup oleh platform] '.$data['catatan'],
            ]);
        }

        Notification::create([
            'user_id' => $komplain->user_id,
            'aktor_id' => ActivityLogger::resolveActorId(),
            'tipe' => Notification::TIPE_KOMPLAIN,
            'judul' => 'Komplain Ditutup',
            'pesan' => sprintf('Komplain "%s" telah ditutup oleh platform.%s', $komplain->subjek, ! empty($data['catatan']) ? ' Catatan: '.$data['catatan'] : ''),
            'url' => route('customer.order-tracking'),
        ]);

        ActivityLogger::log(
            'complaint.close',
            Complaint::class,
            $komplain->complaint_id,
            $lama,
            ['status' => Complaint::STATUS_DITUTUP],
            sprintf('Menutup komplain "%s".', $komplain->subjek)
        );

        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Komplain Ditutup', sprintf('Komplain "%s" ditutup.', $komplain->subjek), route('superadmin.komplain'));

        return back()->with('toast', [
            'message' => 'Komplain ditutup.',
            'icon' => 'task_alt',
        ]);
    }
}
