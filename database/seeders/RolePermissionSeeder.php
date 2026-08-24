<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $matrix = [
            Role::SUPER_ADMIN => '*', // seluruh permission platform

            Role::OWNER => [
                'store.view', 'store.update', 'store.staff.manage',
                'product.view', 'product.create', 'product.update',
                'order.view',
                'payment.view',
                'shipment.view', 'shipment.update',
                'refund.view',
                'wallet.view', 'withdrawal.view', 'withdrawal.request',
                'warehouse.view',
                'production.view',
                'promotion.view', 'promotion.manage',
                'review.view',
                'complaint.view', 'complaint.reply',
                'report.view',
            ],

            Role::ADMIN => [
                'store.view', 'store.update',
                'product.view', 'product.create', 'product.update',
                'order.view', 'order.update',
                'payment.view', 'payment.verify', 'payment.reject',
                'shipment.view', 'shipment.update',
                'refund.view', 'refund.review',
                'warehouse.view',
                'production.view',
                'promotion.view', 'promotion.manage',
                'review.view',
                'complaint.view', 'complaint.reply',
                'report.view',
            ],

            Role::PRODUKSI => [
                'product.view',
                'production.view', 'production.process',
                'warehouse.view',
            ],

            Role::GUDANG => [
                'product.view',
                'order.view',
                'shipment.view',
                'warehouse.view', 'warehouse.stock_in', 'warehouse.stock_out',
                'warehouse.stock_adjust', 'warehouse.transfer',
                'production.view',
            ],

            Role::CUSTOMER => [],
        ];

        $superAdmin = Role::where('nama_role', Role::SUPER_ADMIN)->first();
        $allPermissionIds = Permission::pluck('permission_id')->all();

        foreach ($matrix as $roleName => $permissions) {
            $role = Role::where('nama_role', $roleName)->first();

            if (! $role) {
                continue;
            }

            $permissionIds = $permissions === '*'
                ? $allPermissionIds
                : Permission::whereIn('kode_permission', $permissions)->pluck('permission_id')->all();

            $syncData = [];
            foreach ($permissionIds as $permissionId) {
                $syncData[$permissionId] = ['created_at' => now(), 'updated_at' => now()];
            }

            $role->permissions()->sync($syncData);
        }
    }
}
