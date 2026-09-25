<?php

namespace App\Support;

use App\Models\PermintaanOperasional;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GudangBadgeCounter
{
    public static function counts(): array
    {
        $warehouse = self::activeWarehouse();

        if ($warehouse === null) {
            return [
                'pemindahan' => 0,
                'stok_menipis' => 0,
                'permintaan' => 0,
                'notifikasi' => 0,
            ];
        }

        $warehouseId = $warehouse->warehouse_id;

        return [
            'pemindahan' => StockTransfer::where(fn ($q) => $q
                ->where('from_warehouse_id', $warehouseId)
                ->orWhere('to_warehouse_id', $warehouseId))
                ->where('status', StockTransfer::STATUS_REQUESTED)
                ->count(),
            'stok_menipis' => WarehouseStock::where('warehouse_id', $warehouseId)
                ->where('stok_minimum', '>', 0)
                ->whereRaw('(jumlah_stok - jumlah_direservasi) <= stok_minimum')
                ->count(),
            'permintaan' => PermintaanOperasional::where('pemohon_id', Auth::id())
                ->where('status', PermintaanOperasional::STATUS_PENDING)
                ->count(),
            'notifikasi' => NotificationService::unreadCount(Auth::id()),
        ];
    }

    private static function activeWarehouse(): ?Warehouse
    {
        $assigned = Auth::user()
            ->assignedWarehouses()
            ->wherePivot('status', 'aktif')
            ->where('warehouses.status', Warehouse::STATUS_AKTIF)
            ->orderBy('nama_gudang')
            ->get();

        if ($assigned->isEmpty()) {
            return null;
        }

        $activeId = Session::get('gudang_active_warehouse_id');

        if ($activeId && $assigned->contains('warehouse_id', $activeId)) {
            return $assigned->firstWhere('warehouse_id', $activeId);
        }

        Session::put('gudang_active_warehouse_id', $assigned->first()->warehouse_id);

        return $assigned->first();
    }
}