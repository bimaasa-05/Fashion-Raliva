<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Store;
use App\Support\ActivityLogger;
use Illuminate\Console\Command;

class ReactivateExpiredSuspensions extends Command
{
    protected $signature = 'store:auto-reactivate';

    protected $description = 'Aktifkan kembali toko yang ditangguhkan sementara setelah melewati batas waktu';

    public function handle(): int
    {
        $stores = Store::query()
            ->where('status', Store::STATUS_NONAKTIF)
            ->whereNotNull('ditangguhkan_sampai')
            ->where('ditangguhkan_sampai', '<=', now())
            ->get();

        if ($stores->isEmpty()) {
            $this->line('Tidak ada toko yang perlu diaktifkan kembali.');
            return self::SUCCESS;
        }

        foreach ($stores as $store) {
            $lama = $store->only(['status']);

            $store->update([
                'status' => Store::STATUS_AKTIF,
                'ditangguhkan_sampai' => null,
            ]);

            ActivityLogger::log(
                'store.reactivate_auto',
                Store::class,
                $store->store_id,
                $lama,
                ['status' => Store::STATUS_AKTIF],
                sprintf('Toko "%s" diaktifkan kembali otomatis karena batas waktu penangguhan telah terlewati.', $store->nama_toko)
            );

            Notification::create([
                'user_id' => $store->owner_id,
                'aktor_id' => null,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Toko Diaktifkan Kembali',
                'pesan' => sprintf('Toko "%s" telah diaktifkan kembali dan dapat beroperasi normal.', $store->nama_toko),
                'url' => route('owner.data-toko'),
            ]);

            $this->info("Toko \"{$store->nama_toko}\" diaktifkan kembali.");
        }

        return self::SUCCESS;
    }
}