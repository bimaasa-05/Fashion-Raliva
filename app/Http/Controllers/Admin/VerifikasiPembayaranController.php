<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentVerification;
use App\Models\Role;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use App\Support\AdminContext;
use App\Support\CustomerWalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'menunggu');

        $base = Payment::query()
            ->whereIn('checkout_id', Order::whereIn('store_id', AdminContext::assignedStoreIds())->select('checkout_id'))
            ->with(['checkout.user:user_id,nama_lengkap', 'checkout.orders.store:store_id,nama_toko', 'checkout.orders.items.productVariant.product', 'paymentMethod', 'account', 'proofs', 'verifications.verifier']);

        $stats = [
            'menunggu' => (clone $base)->where('status', Payment::STATUS_MENUNGGU_VERIFIKASI)->count(),
            'diterima' => (clone $base)->where('status', Payment::STATUS_TERVERIFIKASI)->count(),
            'ditolak' => (clone $base)->where('status', Payment::STATUS_DITOLAK)->count(),
            'nominal_menunggu' => (clone $base)->where('status', Payment::STATUS_MENUNGGU_VERIFIKASI)->sum('jumlah'),
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

        $jumlahSaldo = (float) $pembayaran->jumlah_saldo;
        $sisaTransfer = (float) $pembayaran->sisa_transfer;

        try {
            DB::transaction(function () use ($pembayaran, $jumlahSaldo) {
                // Pembayaran campuran: potong saldo untuk bagian yang sudah disanggupi.
                if ($jumlahSaldo > 0) {
                    $checkout = $pembayaran->checkout;
                    $firstOrder = $checkout?->orders()->orderBy('order_id')->first();

                    if (! $checkout?->user || ! $firstOrder) {
                        throw new \RuntimeException('Data checkout tidak valid untuk pemotongan saldo.');
                    }

                    CustomerWalletService::debitForOrder($checkout->user, $firstOrder, $jumlahSaldo);
                }

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
                    ->update(['status' => Order::STATUS_MENUNGGU_PRODUKSI]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('toast', [
                'message' => $e->getMessage().' Pembayaran tidak disetujui.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $logPesan = $jumlahSaldo > 0
            ? sprintf(
                'Memverifikasi pembayaran campuran checkout #%d — saldo Rp %s + transfer Rp %s.',
                $pembayaran->checkout_id,
                number_format($jumlahSaldo, 0, ',', '.'),
                number_format($sisaTransfer, 0, ',', '.')
            )
            : sprintf('Memverifikasi pembayaran Rp %s untuk checkout #%d.', number_format((float) $pembayaran->jumlah, 0, ',', '.'), $pembayaran->checkout_id);

        ActivityLogger::log(
            'admin.payment.approve',
            Payment::class,
            $pembayaran->payment_id,
            $lama,
            ['status' => Payment::STATUS_TERVERIFIKASI],
            $logPesan
        );

        $pesanCustomer = $jumlahSaldo > 0
            ? sprintf(
                'Pembayaran campuran diverifikasi — saldo Rp %s + transfer Rp %s. Pesanan sedang diproses.',
                number_format($jumlahSaldo, 0, ',', '.'),
                number_format($sisaTransfer, 0, ',', '.'),
            )
            : sprintf('Pembayaran sebesar Rp %s telah diverifikasi dan pesanan sedang diproses.', number_format((float) $pembayaran->jumlah, 0, ',', '.'));

        $this->notifyCustomer($pembayaran, 'Pembayaran Diverifikasi', $pesanCustomer);

        // Notifikasi ke Produksi
        NotificationService::sendToRole(
            Role::PRODUKSI,
            Notification::TIPE_SISTEM,
            'Pesanan Siap Diproduksi',
            sprintf('Pembayaran pesanan #%d telah diverifikasi. Menunggu input bahan dari Admin.', $pembayaran->checkout_id),
            ActivityLogger::resolveActorId(),
            route('produksi.data-produksi')
        );

        return back()->with('toast', [
            'message' => 'Pembayaran diverifikasi. Pesanan kini berstatus menunggu produksi.',
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
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_PEMBAYARAN,
                'judul' => $judul,
                'pesan' => $pesan,
                'url' => route('customer.order-tracking'),
            ]);
        }
    }
}
