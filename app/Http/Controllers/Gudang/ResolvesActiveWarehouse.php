<?php

namespace App\Http\Controllers\Gudang;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Helper untuk menentukan gudang aktif milik staf Gudang.
 * Scope: staf hanya boleh mengakses gudang yang ditugaskan (warehouse_staff, status aktif).
 */
trait ResolvesActiveWarehouse
{
    /**
     * Daftar gudang yang ditugaskan ke user Gudang saat ini (status aktif).
     */
    protected function assignedWarehouses()
    {
        return Auth::user()
            ->assignedWarehouses()
            ->wherePivot('status', 'aktif')
            ->where('warehouses.status', Warehouse::STATUS_AKTIF)
            ->orderBy('nama_gudang')
            ->get();
    }

    /**
     * Gudang aktif: diambil dari session, divalidasi terhadap penugasan user.
     * Default ke gudang pertama yang ditugaskan.
     */
    protected function activeWarehouse(): ?Warehouse
    {
        $assigned = $this->assignedWarehouses();

        if ($assigned->isEmpty()) {
            return null;
        }

        $activeId = Session::get('gudang_active_warehouse_id');

        if ($activeId && $assigned->contains('warehouse_id', $activeId)) {
            return $assigned->firstWhere('warehouse_id', $activeId);
        }

        // Fallback: simpan & kembalikan gudang pertama.
        Session::put('gudang_active_warehouse_id', $assigned->first()->warehouse_id);

        return $assigned->first();
    }
}
