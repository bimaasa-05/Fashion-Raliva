<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Models\Notification;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class KomplainController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');

        $stats = [
            'terbuka' => Complaint::where('status', Complaint::STATUS_OPEN)->count(),
            'diproses' => Complaint::where('status', Complaint::STATUS_DIPROSES)->count(),
            'selesai' => Complaint::where('status', Complaint::STATUS_SELESAI)->count(),
            'ditutup' => Complaint::where('status', Complaint::STATUS_DITUTUP)->count(),
        ];

        $complaints = Complaint::query()
            ->with(['user:user_id,nama_lengkap', 'store:store_id,nama_toko,owner_id'])
            ->when(
                in_array($status, [Complaint::STATUS_OPEN, Complaint::STATUS_DIPROSES, Complaint::STATUS_SELESAI, Complaint::STATUS_DITUTUP], true),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByRaw("CASE status WHEN 'open' THEN 0 WHEN 'diproses' THEN 1 ELSE 2 END")
            ->orderByDesc('dibuat_pada')
            ->get()
            ->map(function (Complaint $complaint) {
                $complaint->eskalasi_oleh_sa = $complaint->messages()
                    ->where('sender_id', ActivityLogger::resolveActorId())
                    ->exists();

                return $complaint;
            });

        return view('SuperAdmin.komplain.index', [
            'complaints' => $complaints,
            'stats' => $stats,
            'activeStatus' => $status,
        ]);
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
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Komplain Dieskalasi',
                'pesan' => sprintf('Komplain "%s" dari Customer %s dieskalasikan oleh platform. Segera tangani dan perbarui statusnya.', $komplain->subjek, $komplain->user->nama_lengkap ?? '-'),
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
            'tipe' => Notification::TIPE_KOMPLAIN,
            'judul' => 'Komplain Ditutup',
            'pesan' => sprintf('Komplain "%s" telah ditutup oleh platform.%s', $komplain->subjek, ! empty($data['catatan']) ? ' Catatan: '.$data['catatan'] : ''),
        ]);

        ActivityLogger::log(
            'complaint.close',
            Complaint::class,
            $komplain->complaint_id,
            $lama,
            ['status' => Complaint::STATUS_DITUTUP],
            sprintf('Menutup komplain "%s".', $komplain->subjek)
        );

        return back()->with('toast', [
            'message' => 'Komplain ditutup.',
            'icon' => 'task_alt',
        ]);
    }
}
