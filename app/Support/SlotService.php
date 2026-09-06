<?php

namespace App\Support;

use App\Models\Product;
use App\Models\SlotGrant;
use App\Models\StoreSlotSubscription;
use Illuminate\Support\Facades\Auth;

class SlotService
{
    public static function totalQuota(int $storeId): int
    {
        $grants = (int) SlotGrant::where('store_id', $storeId)->sum('jumlah_slot');

        $legacy = (int) StoreSlotSubscription::where('store_id', $storeId)
            ->where('status', StoreSlotSubscription::STATUS_AKTIF)
            ->sum('jumlah_slot');

        return $grants + $legacy;
    }

    public static function usedSlots(int $storeId): int
    {
        return Product::where('store_id', $storeId)
            ->where('status', Product::STATUS_AKTIF)
            ->count();
    }

    public static function availableSlots(int $storeId): int
    {
        return max(0, static::totalQuota($storeId) - static::usedSlots($storeId));
    }

    public static function progress(int $storeId): int
    {
        $total = static::totalQuota($storeId);

        if ($total <= 0) {
            return 0;
        }

        return (int) round(static::usedSlots($storeId) / $total * 100);
    }

    public static function canAdd(int $storeId, int $qty = 1): bool
    {
        return static::availableSlots($storeId) >= $qty;
    }

    public static function grant(int $storeId, int $jumlah, string $tipe, ?string $keterangan = null, ?int $refId = null, ?string $refType = null): SlotGrant
    {
        if ($jumlah < 1) {
            $jumlah = 1;
        }

        return SlotGrant::create([
            'store_id' => $storeId,
            'jumlah_slot' => $jumlah,
            'tipe' => $tipe,
            'keterangan' => $keterangan,
            'ref_id' => $refId,
            'ref_type' => $refType,
            'created_by' => Auth::id() ?: null,
        ]);
    }

    public static function freeQuota(int $storeId): int
    {
        return (int) SlotGrant::where('store_id', $storeId)
            ->where('tipe', SlotGrant::TIPE_GRATIS)
            ->sum('jumlah_slot');
    }

    public static function setFreeQuota(int $storeId, int $qty): void
    {
        SlotGrant::where('store_id', $storeId)
            ->where('tipe', SlotGrant::TIPE_GRATIS)
            ->delete();

        if ($qty > 0) {
            SlotGrant::create([
                'store_id' => $storeId,
                'jumlah_slot' => $qty,
                'tipe' => SlotGrant::TIPE_GRATIS,
                'keterangan' => 'Kuota gratis bawaan toko.',
                'created_by' => Auth::id() ?: null,
            ]);
        }
    }

    public static function summaries(iterable $stores): array
    {
        $result = [];

        foreach ($stores as $store) {
            $id = $store->store_id;
            $used = static::usedSlots($id);

            $result[$id] = [
                'store_id' => $id,
                'nama_toko' => $store->nama_toko ?? '-',
                'total' => static::totalQuota($id),
                'used' => static::usedSlots($id),
                'available' => static::availableSlots($id),
                'progress' => static::progress($id),
                'free_quota' => static::freeQuota($id),
            ];
        }

        $ids = array_keys($result);

        return [
            'by_store' => $result,
            'totals' => [
                'toko' => count($result),
                'kuota' => array_sum(array_column($result, 'total')),
                'used' => array_sum(array_column($result, 'used')),
                'available' => array_sum(array_column($result, 'available')),
            ],
        ];
    }
}
