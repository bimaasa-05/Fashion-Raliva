<?php

namespace App\Console\Commands;

use App\Models\AdSlot;
use App\Models\Notification;
use App\Support\ActivityLogger;
use Illuminate\Console\Command;

class AutoProcessAdSlots extends Command
{
    protected $signature = 'ad:auto-process';

    protected $description = 'Aktifkan iklan terjadwal saat tanggal mulai tiba dan nonaktifkan iklan yang melewati tanggal selesai';

    public function handle(): int
    {
        $today = now()->toDateString();
        $aktif = 0;
        $nonaktif = 0;

        $mulaiTayang = AdSlot::query()
            ->where('status', AdSlot::STATUS_TERJADWAL)
            ->whereNotNull('tanggal_mulai')
            ->whereNotNull('tanggal_selesai')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->get();

        foreach ($mulaiTayang as $slot) {
            $slot->loadMissing(['store', 'product']);

            $slot->update(['status' => AdSlot::STATUS_AKTIF]);
            $aktif++;

            ActivityLogger::log(
                'ad_slot.auto_activate',
                AdSlot::class,
                $slot->ad_slot_id,
                ['status' => AdSlot::STATUS_TERJADWAL],
                ['status' => AdSlot::STATUS_AKTIF],
                'Iklan peringkat otomatis aktif (tanggal mulai tiba).'
            );

            $ownerId = $slot->store?->owner_id;
            if ($ownerId) {
                Notification::create([
                    'user_id' => $ownerId,
                    'aktor_id' => null,
                    'tipe' => Notification::TIPE_PROMO,
                    'judul' => 'Iklan Mulai Tayang',
                    'pesan' => sprintf('Iklan "%s" periode %s s/d %s mulai tayang.', $slot->product->nama_produk ?? '-', $slot->tanggal_mulai?->translatedFormat('d M Y') ?? '-', $slot->tanggal_selesai?->translatedFormat('d M Y') ?? '-'),
                    'url' => route('owner.peringkat-iklan'),
                ]);
            }
        }

        $melewatiSelesai = AdSlot::query()
            ->whereIn('status', [AdSlot::STATUS_AKTIF, AdSlot::STATUS_TERJADWAL])
            ->whereNotNull('tanggal_selesai')
            ->whereDate('tanggal_selesai', '<', $today)
            ->get();

        foreach ($melewatiSelesai as $slot) {
            $lama = $slot->status;
            $slot->update(['status' => AdSlot::STATUS_NONAKTIF]);
            $nonaktif++;

            ActivityLogger::log(
                'ad_slot.auto_expire',
                AdSlot::class,
                $slot->ad_slot_id,
                ['status' => $lama],
                ['status' => AdSlot::STATUS_NONAKTIF],
                'Iklan peringkat otomatis nonaktif (periode berakhir).'
            );
        }

        if ($aktif > 0 || $nonaktif > 0) {
            $this->info("{$aktif} iklan diaktifkan, {$nonaktif} iklan dinonaktifkan.");
        } else {
            $this->line('Tidak ada iklan yang perlu diubah statusnya.');
        }

        return self::SUCCESS;
    }
}
