<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class ManajemenRoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->get();

        return view('SuperAdmin.manajemen-role.index', [
            'roles' => $roles,
            'stats' => [
                'total' => $roles->count(),
                'aktif' => $roles->where('status', 'aktif')->count(),
                'total_permissions' => Permission::count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_role' => 'required|string|max:50|unique:roles,nama_role',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $role = Role::create([
            'nama_role' => $data['nama_role'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'status' => 'aktif',
        ]);

        ActivityLogger::log(
            'role.create',
            Role::class,
            $role->role_id,
            null,
            ['nama_role' => $role->nama_role],
            sprintf('Menambahkan role baru "%s".', $role->nama_role)
        );

        return back()->with('toast', [
            'message' => "Role \"{$role->nama_role}\" berhasil ditambahkan.",
            'icon' => 'task_alt',
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'nama_role' => 'required|string|max:50|unique:roles,nama_role,'.$role->role_id.',role_id',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $old = $role->toArray();

        $role->update([
            'nama_role' => $data['nama_role'],
            'deskripsi' => $data['deskripsi'] ?? null,
        ]);

        ActivityLogger::log(
            'role.update',
            Role::class,
            $role->role_id,
            $old,
            $role->toArray(),
            sprintf('Mengubah data role "%s".', $role->nama_role)
        );

        return back()->with('toast', [
            'message' => "Role \"{$role->nama_role}\" berhasil diperbarui.",
            'icon' => 'task_alt',
        ]);
    }

    public function toggleStatus(Role $role)
    {
        if ($role->nama_role === Role::SUPER_ADMIN) {
            return back()->with('toast', [
                'message' => 'Role Super Admin tidak dapat dinonaktifkan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if ($role->users()->count() > 0 && $role->status === 'aktif') {
            return back()->with('toast', [
                'message' => "Role \"{$role->nama_role}\" masih memiliki {$role->users()->count()} user. Nonaktifkan user terlebih dahulu.",
                'icon' => 'gpp_maybe',
            ]);
        }

        $oldStatus = $role->status;
        $newStatus = $role->status === 'aktif' ? 'nonaktif' : 'aktif';
        $role->update(['status' => $newStatus]);

        ActivityLogger::log(
            'role.toggle_status',
            Role::class,
            $role->role_id,
            ['status' => $oldStatus],
            ['status' => $newStatus],
            sprintf('Mengubah status role "%s" dari %s ke %s.', $role->nama_role, $oldStatus, $newStatus)
        );

        return back()->with('toast', [
            'message' => "Role \"{$role->nama_role}\" berhasil di{$newStatus}.",
            'icon' => 'task_alt',
        ]);
    }

    public function detail(Role $role)
    {
        $role->load(['users' => function ($q) {
            $q->select('user_id', 'nama_lengkap', 'email', 'role_id', 'status');
        }]);

        $allPermissions = Permission::where('status', 'aktif')->orderBy('kode_permission')->get();

        $permissionGroups = [
            'User Management' => ['user.view', 'user.create', 'user.update', 'role.manage'],
            'Store Management' => ['store.view', 'store.create', 'store.update', 'store.verify', 'store.staff.manage'],
            'Product Management' => ['product.view', 'product.create', 'product.update', 'product.moderate'],
            'Order & Payment' => ['order.view', 'order.update', 'payment.view', 'payment.verify', 'payment.reject'],
            'Shipment & Refund' => ['shipment.view', 'shipment.update', 'refund.view', 'refund.review', 'refund.approve'],
            'Wallet & Withdrawal' => ['wallet.view', 'withdrawal.view', 'withdrawal.request', 'withdrawal.approve'],
            'Warehouse & Production' => ['warehouse.view', 'warehouse.stock_in', 'warehouse.stock_out', 'warehouse.stock_adjust', 'warehouse.transfer', 'production.view', 'production.process', 'production.qc'],
            'Promotion & Review' => ['promotion.view', 'promotion.manage', 'review.view', 'review.moderate'],
            'Complaint & Report' => ['complaint.view', 'complaint.reply', 'report.view', 'setting.manage'],
        ];

        $groupedPermissions = [];
        foreach ($permissionGroups as $groupName => $codes) {
            $groupedPermissions[$groupName] = $allPermissions->filter(fn ($p) => in_array($p->kode_permission, $codes))->values();
        }

        return view('SuperAdmin.manajemen-role.detail', [
            'role' => $role,
            'allPermissions' => $allPermissions,
            'groupedPermissions' => $groupedPermissions,
        ]);
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $permissionIds = $request->input('permissions', []);

        $role->permissions()->sync($permissionIds);

        ActivityLogger::log(
            'role.permissions.update',
            Role::class,
            $role->role_id,
            ['permission_ids' => $role->permissions->pluck('permission_id')->toArray()],
            ['permission_ids' => $permissionIds],
            sprintf('Memperbarui permission role "%s" (%d permission).', $role->nama_role, count($permissionIds))
        );

        return back()->with('toast', [
            'message' => "Permission role \"{$role->nama_role}\" berhasil diperbarui.",
            'icon' => 'task_alt',
        ]);
    }
}
