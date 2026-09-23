<?php

namespace App\Support;

use App\Models\Checkout;
use App\Models\Complaint;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Refund;
use App\Models\Setting;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;

class OrderAutoComplete
{
    public const SETTING_KEY = 'konfirmasi_selesai_hari';

    public const OFFLINE_BATAS_HARI = 3;

    public static function batasHari(): int
    {
        return max(1, (int) Setting::get(self::SETTING_KEY, '5'));
    }

    /**
     * Selesaikan otomatis:
     *  - order berstatus dikirim yang telah melewati batas konfirmasi, dan
     *  - order offline berstatus siap_kirim yang sudah melewati batas ambil
     *    tanpa konfirmasi (tidak memiliki refund/komplain aktif).
     *
     * @return int jumlah order yang diselesaikan
     */
    public static function selesaikanOtomatis(): int
    {
        $batas = now()->subDays(self::batasHari());

        $candidates = Order::where('status', Order::STATUS_DIKIRIM)
            ->with(['shipments', 'store', 'checkout'])
            ->get()
            ->filter(function (Order $order) use ($batas) {
                $dikirimPada = $order->shipments
                    ->where('status', Shipment::STATUS_DIKIRIM)
                    ->pluck('dikirim_pada')
                    ->filter()
                    ->max();

                if (! $dikirimPada) {
                    $dikirimPada = $order->updated_at;
                }

                return $dikirimPada !== null && $dikirimPada->lte($batas);
            });

        $siapDiambil = Order::where('tipe_pesanan', Order::TIPE_PESANAN_OFFLINE)
            ->where('status', Order::STATUS_SIAP_KIRIM)
            ->where('updated_at', '<=', now()->subDays(self::OFFLINE_BATAS_HARI))
            ->with(['store', 'checkout'])
            ->get();

        $candidates = $candidates->merge($siapDiambil);

        $selesai = 0;

        foreach ($candidates as $order) {
            if (self::adaRefundAtauKomplainAktif($order)) {
                continue;
            }

            $selesai += $order->status === Order::STATUS_SIAP_KIRIM
                ? self::selesaikanDiambil($order)
                : self::selesaikan($order);
        }

        return $selesai;
    }

    private static function adaRefundAtauKomplainAktif(Order $order): bool
    {
        if (Refund::where('order_id', $order->order_id)
            ->whereIn('status', [Refund::STATUS_REQUESTED, Refund::STATUS_ESKALASI, Refund::STATUS_DISETUJUI])
            ->exists()) {
            return true;
        }

        return Complaint::where('order_id', $order->order_id)
            ->whereIn('status', [Complaint::STATUS_OPEN, Complaint::STATUS_DIPROSES, Complaint::STATUS_ESKALASI])
            ->exists();
    }

    private static function selesaikanDiambil(Order $order): int
    {
        return DB::transaction(function () use ($order) {
            $locked = Order::whereKey($order->order_id)->lockForUpdate()->first();

            if (! $locked || ! $locked->isOffline() || $locked->status !== Order::STATUS_SIAP_KIRIM) {
                return 0;
            }

            $locked->update([
                'status' => Order::STATUS_SELESAI,
                'diambil_pada' => now(),
            ]);
            WalletService::creditOrder($locked);
            StockDeductionService::deductForOrder($locked);

            $batasHari = self::OFFLINE_BATAS_HARI;

            if ($locked->store && $locked->store->owner_id) {
                Notification::create([
                    'user_id' => $locked->store->owner_id,
                    'tipe' => Notification::TIPE_ORDER,
                    'judul' => 'Pesanan Diambil Otomatis',
                    'pesan' => sprintf('Pesanan offline %s diselesaikan otomatis karena sudah %s hari siap diambil tanpa konfirmasi.', $locked->nomor_order, $batasHari),
                ]);
            }

            $customerId = Checkout::whereKey($locked->checkout_id)->value('user_id');
            if ($customerId) {
                Notification::create([
                    'user_id' => $customerId,
                    'tipe' => Notification::TIPE_ORDER,
                    'judul' => 'Pesanan Ditandai Selesai',
                    'pesan' => sprintf('Pesanan %s dianggap selesai karena sudah %s hari tidak diambil. Silakan cek detail pesanan Anda.', $locked->nomor_order, $batasHari),
                    'url' => route('customer.order-tracking', ['order' => $locked->order_id]),
                ]);
            }

            ActivityLogger::log(
                'order.auto_picked_up',
                Order::class,
                $locked->order_id,
                ['status' => Order::STATUS_SIAP_KIRIM],
                ['status' => Order::STATUS_SELESAI, 'batas_hari' => $batasHari],
                sprintf('Pesanan offline %s diselesaikan otomatis karena melewati %s hari siap diambil.', $locked->nomor_order, $batasHari)
            );

            return 1;
        });
    }

    private static function selesaikan(Order $order): int
    {
        return DB::transaction(function () use ($order) {
            $locked = Order::whereKey($order->order_id)->lockForUpdate()->first();

            if (! $locked || $locked->status !== Order::STATUS_DIKIRIM) {
                return 0;
            }

            $locked->update(['status' => Order::STATUS_SELESAI]);
            WalletService::creditOrder($locked);
            StockDeductionService::deductForOrder($locked);

            $locked->shipments()
                ->where('status', Shipment::STATUS_DIKIRIM)
                ->lockForUpdate()
                ->get()
                ->each(function (Shipment $shipment) {
                    if ($shipment->canTransitionTo(Shipment::STATUS_DITERIMA)) {
                        $shipment->update(['status' => Shipment::STATUS_DITERIMA, 'diterima_pada' => now()]);
                    }
                });

            $batasHari = self::batasHari();

            if ($locked->store && $locked->store->owner_id) {
                Notification::create([
                    'user_id' => $locked->store->owner_id,
                    'tipe' => Notification::TIPE_ORDER,
                    'judul' => 'Pesanan Selesai Otomatis',
                    'pesan' => sprintf('Pesanan %s diselesaikan otomatis karena melewati %s hari tanpa konfirmasi customer.', $locked->nomor_order, $batasHari),
                ]);
            }

            $customerId = Checkout::whereKey($locked->checkout_id)->value('user_id');
            if ($customerId) {
                Notification::create([
                    'user_id' => $customerId,
                    'tipe' => Notification::TIPE_ORDER,
                    'judul' => 'Pesanan Diselesaikan Otomatis',
                    'pesan' => sprintf('Pesanan %s dianggap selesai karena tidak dikonfirmasi dalam %s hari. Silakan cek detail pesanan Anda.', $locked->nomor_order, $batasHari),
                    'url' => route('customer.order-tracking', ['order' => $locked->order_id]),
                ]);
            }

            ActivityLogger::log(
                'order.auto_completed',
                Order::class,
                $locked->order_id,
                ['status' => Order::STATUS_DIKIRIM],
                ['status' => Order::STATUS_SELESAI, 'batas_hari' => $batasHari],
                sprintf('Pesanan %s diselesaikan otomatis karena melewati %s hari tanpa konfirmasi customer.', $locked->nomor_order, $batasHari)
            );

            return 1;
        });
    }
}