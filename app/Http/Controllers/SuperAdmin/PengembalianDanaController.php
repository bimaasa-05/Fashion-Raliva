<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Refund;
use App\Services\RefundCompletionService;
use App\Support\ActivityLogger;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengembalianDanaController extends Controller
{
    public function index(Request $request)
    {
        $query = Refund::query()
            ->with(['order.store', 'order.checkout.payment.paymentMethod', 'payment', 'requester'])
            ->orderByDesc('diajukan_pada');

        $stats = [
            'semua' => Refund::count(),
            'requested' => Refund::where('status', Refund::STATUS_REQUESTED)->count(),
            'nominal_menunggu' => (float) Refund::where('status', Refund::STATUS_REQUESTED)->sum('jumlah'),
            'disetujui' => Refund::where('status', Refund::STATUS_DISETUJUI)->count(),
            Refund::STATUS_ESKALASI => Refund::where('status', Refund::STATUS_ESKALASI)->count(),
            'selesai' => Refund::where('status', Refund::STATUS_SELESAI)->count(),
            'ditolak' => Refund::where('status', Refund::STATUS_DITOLAK)->count(),
        ];

        $refunds = $query->paginate(20)->withQueryString();

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

        $refund->loadMissing('order.checkout.payment.paymentMethod');

        $payment = $refund->order?->checkout?->payment;
        $isSaldoAkun = $payment && $payment->paymentMethod?->kode_metode === PaymentMethod::KODE_SALDO_AKUN;

        $data = $request->validate([
            'file_bukti' => [($isSaldoAkun ? 'nullable' : 'required'), 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'deskripsi_bukti' => ['nullable', 'string', 'max:1000'],
        ], [
            'file_bukti.required' => 'Bukti transfer wajib dilampirkan.',
            'file_bukti.mimes' => 'Bukti transfer harus berupa JPG, PNG, atau PDF.',
            'file_bukti.max' => 'Ukuran bukti transfer maksimal 5 MB.',
            'deskripsi_bukti.max' => 'Deskripsi bukti maksimal 1000 karakter.',
        ]);

        $path = $request->hasFile('file_bukti')
            ? $request->file('file_bukti')->store('bukti-refund/'.$refund->refund_id, 'public')
            : null;

        $lama = $refund->only(['status', 'reviewed_by']);

        try {
            $refund->update([
                'status' => Refund::STATUS_DISETUJUI,
                'reviewed_by' => ActivityLogger::resolveActorId(),
            ]);

            RefundCompletionService::complete($refund, $path, $data['deskripsi_bukti'] ?? null);
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'Saldo toko tidak cukup')) {
                return back()->with('toast', [
                    'message' => 'Saldo toko tidak cukup untuk menyelesaikan refund ini.',
                    'icon' => 'gpp_maybe',
                ]);
            }

            if (
                str_contains($e->getMessage(), 'tidak terhubung ke toko')
                || str_contains($e->getMessage(), 'Wallet toko tidak ditemukan')
                || str_contains($e->getMessage(), 'sudah berubah')
            ) {
                return back()->with('toast', [
                    'message' => 'Refund tidak dapat diselesaikan: '.$e->getMessage(),
                    'icon' => 'gpp_maybe',
                ]);
            }

            throw $e;
        }

        ActivityLogger::log(
            'refund.approve',
            Refund::class,
            $refund->refund_id,
            $lama,
            ['status' => Refund::STATUS_SELESAI, 'file_bukti' => $path],
            sprintf('Menyetujui dan menyelesaikan refund %s sebesar Rp %s untuk pesanan %s (bukti terlampir).', $refund->tipe_refund, number_format((float) $refund->jumlah, 0, ',', '.'), $refund->order->nomor_order ?? '-')
        );

        $this->notifyPihak($refund, 'Refund Selesai', sprintf('Dana refund sebesar Rp %s telah dikirim ke akun Anda.', number_format((float) $refund->jumlah, 0, ',', '.')));
        Notification::fireSelf(Notification::TIPE_PEMBAYARAN, 'Refund Disetujui dan Selesai', sprintf('Refund Rp %s disetujui dan ditandai selesai.', number_format((float) $refund->jumlah, 0, ',', '.')), route('superadmin.pengembalian-dana'));

        return back()->with('toast', [
            'message' => sprintf('Refund Rp %s disetujui dan ditandai selesai.', number_format((float) $refund->jumlah, 0, ',', '.')),
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
        Notification::fireSelf(Notification::TIPE_PEMBAYARAN, 'Refund Ditolak', sprintf('Refund Rp %s ditolak.', number_format((float) $refund->jumlah, 0, ',', '.')), route('superadmin.pengembalian-dana'));

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

        $data = $request->validate([
            'file_bukti' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'deskripsi_bukti' => ['nullable', 'string', 'max:1000'],
        ], [
            'file_bukti.required' => 'Bukti refund wajib dilampirkan.',
            'file_bukti.mimes' => 'Bukti refund harus berupa JPG, PNG, atau PDF.',
            'file_bukti.max' => 'Ukuran bukti refund maksimal 5 MB.',
            'deskripsi_bukti.max' => 'Deskripsi bukti maksimal 1000 karakter.',
        ]);

        $path = $request->file('file_bukti')->store('bukti-refund/'.$refund->refund_id, 'public');

        if ($refund->file_bukti && $refund->file_bukti !== $path) {
            Storage::disk('public')->delete($refund->file_bukti);
        }

        try {
            RefundCompletionService::complete($refund, $path, $data['deskripsi_bukti'] ?? null);
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'Saldo toko tidak cukup')) {
                return back()->with('toast', [
                    'message' => 'Saldo toko tidak cukup untuk menyelesaikan refund ini.',
                    'icon' => 'gpp_maybe',
                ]);
            }

            if (
                str_contains($e->getMessage(), 'tidak terhubung ke toko')
                || str_contains($e->getMessage(), 'Wallet toko tidak ditemukan')
                || str_contains($e->getMessage(), 'sudah berubah')
            ) {
                return back()->with('toast', [
                    'message' => 'Refund tidak dapat diselesaikan: '.$e->getMessage(),
                    'icon' => 'gpp_maybe',
                ]);
            }

            throw $e;
        }

        $this->notifyPihak($refund, 'Refund Selesai', sprintf('Dana refund sebesar Rp %s telah dikirim ke akun Anda.', number_format((float) $refund->jumlah, 0, ',', '.')));
        Notification::fireSelf(Notification::TIPE_PEMBAYARAN, 'Refund Selesai', sprintf('Refund Rp %s ditandai selesai.', number_format((float) $refund->jumlah, 0, ',', '.')), route('superadmin.pengembalian-dana'));

        return back()->with('toast', [
            'message' => 'Refund ditandai selesai dengan bukti terlampir.',
            'icon' => 'task_alt',
        ]);
    }

    private function notifyPihak(Refund $refund, string $judul, string $pesan): void
    {
        Notification::create([
            'user_id' => $refund->requested_by,
            'aktor_id' => ActivityLogger::resolveActorId(),
            'tipe' => Notification::TIPE_PEMBAYARAN,
            'judul' => $judul,
            'pesan' => $pesan,
            'url' => route('customer.order-tracking'),
        ]);

        $ownerId = optional($refund->order?->store)->owner_id;

        if ($ownerId) {
            Notification::create([
                'user_id' => $ownerId,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_PEMBAYARAN,
                'judul' => $judul.' (Toko Anda)',
                'pesan' => sprintf('%s | Pesanan %s.', $pesan, $refund->order->nomor_order ?? '-'),
                'url' => route('owner.pengembalian-dana'),
            ]);
        }
    }
}
