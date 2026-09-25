<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Order;
use App\Models\Role;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class RemindQcGagal extends Command
{
    protected $signature = 'qc:remind';

    protected $description = 'Pengingat ulang harian untuk pesanan QC Gagal yang belum ditanggapi Admin (>24 jam)';

    public function handle(): int
    {
        $orders = Order::where('status', Order::STATUS_MENUNGGU_QC)
            ->whereNotNull('qc_perlu_admin_pada')
            ->where('qc_perlu_admin_pada', '<=', now()->subDay())
            ->get();

        foreach ($orders as $order) {
            NotificationService::sendToRoleInStores(
                Role::ADMIN,
                [$order->store_id],
                Notification::TIPE_SISTEM,
                'Pengingat: QC Gagal Belum Ditanggapi',
                sprintf(
                    'Pesanan %s ditandai QC gagal pada %s dan belum ditanggapi. Segera pilih Rework Produksi atau Lanjut QC.',
                    $order->nomor_order,
                    $order->qc_perlu_admin_pada?->translatedFormat('d M Y H:i') ?? '-'
                ),
                null,
                route('admin.pesanan', ['status' => Order::STATUS_MENUNGGU_QC])
            );
        }

        $this->info(sprintf('%d pesanan diingatkan.', $orders->count()));

        return self::SUCCESS;
    }
}
