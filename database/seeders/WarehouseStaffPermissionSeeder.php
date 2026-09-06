<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\WarehouseStaff;
use App\Models\WarehouseStaffPermission;
use Illuminate\Database\Seeder;

class WarehouseStaffPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua warehouse staff aktif
        $warehouseStaffs = WarehouseStaff::where('status', WarehouseStaff::STATUS_AKTIF)->get();

        // Permission default untuk staf gudang (bisa dikustomisasi nanti via UI)
        $defaultPermissions = [
            'warehouse.view',
            'warehouse.stock_in',
            'warehouse.stock_out',
            'warehouse.stock_adjust',
            'warehouse.transfer',
            'warehouse.damage',
            'warehouse.stock_check',
        ];

        $permissionIds = Permission::whereIn('kode_permission', $defaultPermissions)
            ->pluck('permission_id')
            ->all();

        foreach ($warehouseStaffs as $staff) {
            foreach ($permissionIds as $permissionId) {
                WarehouseStaffPermission::updateOrCreate(
                    [
                        'warehouse_staff_id' => $staff->warehouse_staff_id,
                        'permission_id' => $permissionId,
                    ],
                    [
                        'status' => WarehouseStaffPermission::STATUS_AKTIF,
                    ]
                );
            }
        }
    }
}
