<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Refund;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class PengembalianDanaController extends Controller
{
    public function index(Request $request)
    {
        $refunds = Refund::query()
            ->with(['order.store', 'payment', 'requester'])
            ->orderByRaw("CASE status WHEN 'requested' THEN 0 WHEN 'disetujui' THEN 1 ELSE 2 END")
            ->orderByDesc('diajukan_pada')
            ->get();

        $stats = [
            'semua' => $refunds->count(),
            'requested' => $refunds->where('status', Refund::STATUS_REQUESTED)->count(),
            'nominal_menunggu' => (float) $refunds->where('status', Refund::STATUS_REQUESTED)->sum('jumlah'),
            'disetujui' => $refunds->where('status', Refund::STATUS_DISETUJUI)->count(),
            'selesai' => $refunds->where('status', Refund::STATUS_SELESAI)->count(),
            'ditolak' => $refunds->where('status', Refund::STATUS_DITOLAK)->count(),
        ];

        return view('SuperAdmin.pengembalian-dana.index', [
            'refunds' => $refunds,
            'stats' => $stats,
        ]);
    }

    public function setujui(Request $request, Refund $refund)
    {
        if ($refund->status !== Refund::STATUS_REQUESTED) {
            return back()->with('toast', [
                'message' => 'Hanya refund berstatus menunggu yang dapat disetujui.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $refund->only(['status', 'reviewed_by']);

        $refund->update([
            'status' => Refund::STATUS_DISETUJUI,
            'reviewed_by' => ActivityLogger::resolveActorId(),
        ]);

        ActivityLogger::log(
            'refund.approve',
            Refund::class,
            $refund->refund_id,
            $lama,
            ['status' => Refund::STATUS_DISETUJUI],
            sprintf('Menyetujui refund %s sebesar Rp %s untuk pesanan %s.', $refund->tipe_refund, number_format((float) $refund->jumlah, 0, ',', '.'), $refund->order->nomor_order ?? '-')
        );

        $this->notifyPihak($refund, 'Refund Disetujui', sprintf('Pengajuan refund Anda (%s) sebesar Rp %s telah disetujui dan sedang diproses.', $refund->tipe_refund === Refund::TIPE_FULL ? 'penuh' : 'parsial', number_format((float) $refund->jumlah, 0, ',', '.')));

        return back()->with('toast', [
            'message' => sprintf('Refund Rp %s disetujui dan diproses.', number_format((float) $refund->jumlah, 0, ',', '.')),
            'icon' => 'task_alt',
        ]);
    }

    public function tolak(Request $request, Refund $refund)
    {
        if ($refund->status !== Refund::STATUS_REQUESTED) {
            return back()->with('toast', [
                'message' => 'Hanya refund berstatus menunggu yang dapat ditolak.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $lama = $refund->only(['status', 'reviewed_by']);

        $refund->update([
            'status' => Refund::STATUS_DITOLAK,
            'reviewed_by' => ActivityLogger::resolveActorId(),
            'alasan_penolakan' => $data['alasan'],
        ]);

        ActivityLogger::log(
            'refund.reject',
            Refund::class,
            $refund->refund_id,
            $lama,
            ['status' => Refund::STATUS_DITOLAK, 'alasan_penolakan' => $data['alasan']],
            sprintf('Menolak refund pesanan %s dengan alasan: %s', $refund->order->nomor_order ?? '-', $data['alasan'])
        );

        $this->notifyPihak($refund, 'Refund Ditolak', sprintf('Pengajuan refund Anda ditolak. Alasan: %s', $data['alasan']));

        return back()->with('toast', [
            'message' => 'Pengajuan refund ditolak. Customer akan dinotifikasi.',
            'icon' => 'block',
        ]);
    }

    public function selesaikan(Request $request, Refund $refund)
    {
        if ($refund->status !== Refund::STATUS_DISETUJUI) {
            return back()->with('toast', [
                'message' => 'Hanya refund berstatus disetujui yang dapat diselesaikan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $refund->only(['status']);

        $refund->update([
            'status' => Refund::STATUS_SELESAI,
            'selesai_pada' => now(),
        ]);

        ActivityLogger::log(
            'refund.complete',
            Refund::class,
            $refund->refund_id,
            $lama,
            ['status' => Refund::STATUS_SELESAI],
            sprintf('Menyelesaikan refund sebesar Rp %s untuk pesanan %s.', number_format((float) $refund->jumlah, 0, ',', '.'), $refund->order->nomor_order ?? '-')
        );

        $this->notifyPihak($refund, 'Refund Selesai', sprintf('Dana refund sebesar Rp %s telah dikirim ke akun Anda.', number_format((float) $refund->jumlah, 0, ',', '.')));

        return back()->with('toast', [
            'message' => 'Refund ditandai selesai.',
            'icon' => 'task_alt',
        ]);
    }

    private function notifyPihak(Refund $refund, string $judul, string $pesan): void
    {
        Notification::create([
            'user_id' => $refund->requested_by,
            'tipe' => Notification::TIPE_PEMBAYARAN,
            'judul' => $judul,
            'pesan' => $pesan,
        ]);

        $ownerId = optional($refund->order?->store)->owner_id;

        if ($ownerId) {
            Notification::create([
                'user_id' => $ownerId,
                'tipe' => Notification::TIPE_PEMBAYARAN,
                'judul' => $judul.' (Toko Anda)',
                'pesan' => sprintf('%s | Pesanan %s.', $pesan, $refund->order->nomor_order ?? '-'),
            ]);
        }
    }
}
