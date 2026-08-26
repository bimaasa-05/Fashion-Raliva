<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentVerification;
use App\Support\ActivityLogger;
use App\Support\AdminContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'menunggu');

        $base = Payment::query()
            ->whereIn('checkout_id', Order::whereIn('store_id', AdminContext::assignedStoreIds())->select('checkout_id'))
            ->with(['checkout.user:user_id,nama_lengkap', 'checkout.orders.store:store_id,nama_toko', 'paymentMethod', 'proofs', 'verifications.verifier']);

        $stats = [
            'menunggu' => (clone $base)->where('status', Payment::STATUS_MENUNGGU_VERIFIKASI)->count(),
            'diterima' => (clone $base)->where('status', Payment::STATUS_TERVERIFIKASI)->count(),
            'ditolak' => (clone $base)->where('status', Payment::STATUS_DITOLAK)->count(),
        ];

        $payments = match ($tab) {
            'diterima' => (clone $base)->where('status', Payment::STATUS_TERVERIFIKASI)->orderByDesc('dibayar_pada')->get(),
            'ditolak' => (clone $base)->where('status', Payment::STATUS_DITOLAK)->orderByDesc('updated_at')->get(),
            default => (clone $base)->where('status', Payment::STATUS_MENUNGGU_VERIFIKASI)->orderByDesc('updated_at')->get(),
        };

        return view('Admin.verifikasi-pembayaran.index', [
            'payments' => $payments,
            'stats' => $stats,
            'activeTab' => in_array($tab, ['menunggu', 'diterima', 'ditolak'], true) ? $tab : 'menunggu',
        ]);
    }

    public function setujui(Request $request, Payment $pembayaran)
    {
        if (! $this->inScope($pembayaran)) {
            return back()->with('toast', [
                'message' => 'Pembayaran ini di luar scope toko yang Anda tugaskan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if ($pembayaran->status !== Payment::STATUS_MENUNGGU_VERIFIKASI) {
            return back()->with('toast', [
                'message' => 'Hanya pembayaran berstatus menunggu verifikasi yang dapat disetujui.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $pembayaran->only(['status']);

        DB::transaction(function () use ($pembayaran) {
            PaymentVerification::create([
                'payment_id' => $pembayaran->payment_id,
                'verifier_id' => ActivityLogger::resolveActorId(),
                'status' => PaymentVerification::STATUS_DITERIMA,
                'diverifikasi_pada' => now(),
            ]);

            $pembayaran->update([
                'status' => Payment::STATUS_TERVERIFIKASI,
                'dibayar_pada' => now(),
            ]);

            $pembayaran->checkout->update(['status' => Checkout::STATUS_DIBAYAR]);

            Order::where('checkout_id', $pembayaran->checkout_id)
                ->where('status', Order::STATUS_PENDING_PAYMENT)
                ->update(['status' => Order::STATUS_DIBAYAR]);
        });

        ActivityLogger::log(
            'admin.payment.approve',
            Payment::class,
            $pembayaran->payment_id,
            $lama,
            ['status' => Payment::STATUS_TERVERIFIKASI],
            sprintf('Memverifikasi pembayaran Rp %s untuk checkout #%d.', number_format((float) $pembayaran->jumlah, 0, ',', '.'), $pembayaran->checkout_id)
        );

        $this->notifyCustomer($pembayaran, 'Pembayaran Diverifikasi', sprintf('Pembayaran sebesar Rp %s telah diverifikasi dan pesanan sedang diproses.', number_format((float) $pembayaran->jumlah, 0, ',', '.')));

        return back()->with('toast', [
            'message' => 'Pembayaran diverifikasi. Pesanan kini berstatus dibayar.',
            'icon' => 'task_alt',
        ]);
    }

    public function tolak(Request $request, Payment $pembayaran)
    {
        if (! $this->inScope($pembayaran)) {
            return back()->with('toast', [
                'message' => 'Pembayaran ini di luar scope toko yang Anda tugaskan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if ($pembayaran->status !== Payment::STATUS_MENUNGGU_VERIFIKASI) {
            return back()->with('toast', [
                'message' => 'Hanya pembayaran berstatus menunggu verifikasi yang dapat ditolak.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $lama = $pembayaran->only(['status']);

        PaymentVerification::create([
            'payment_id' => $pembayaran->payment_id,
            'verifier_id' => ActivityLogger::resolveActorId(),
            'status' => PaymentVerification::STATUS_DITOLAK,
            'alasan' => $data['alasan'],
            'diverifikasi_pada' => now(),
        ]);

        $pembayaran->update(['status' => Payment::STATUS_DITOLAK]);

        ActivityLogger::log(
            'admin.payment.reject',
            Payment::class,
            $pembayaran->payment_id,
            $lama,
            ['status' => Payment::STATUS_DITOLAK, 'alasan' => $data['alasan']],
            sprintf('Menolak pembayaran checkout #%d dengan alasan: %s', $pembayaran->checkout_id, $data['alasan'])
        );

        $this->notifyCustomer($pembayaran, 'Pembayaran Ditolak', sprintf('Bukti pembayaran Anda ditolak. Alasan: %s. Silakan unggah ulang bukti yang benar.', $data['alasan']));

        return back()->with('toast', [
            'message' => 'Pembayaran ditolak. Customer dinotifikasi untuk upload ulang.',
            'icon' => 'block',
        ]);
    }

    private function inScope(Payment $pembayaran): bool
    {
        $storeIds = AdminContext::assignedStoreIds();

        return Order::where('checkout_id', $pembayaran->checkout_id)
            ->whereIn('store_id', $storeIds)
            ->exists();
    }

    private function notifyCustomer(Payment $pembayaran, string $judul, string $pesan): void
    {
        $userId = $pembayaran->checkout?->user_id;

        if ($userId) {
            Notification::create([
                'user_id' => $userId,
                'tipe' => Notification::TIPE_PEMBAYARAN,
                'judul' => $judul,
                'pesan' => $pesan,
            ]);
        }
    }
}
