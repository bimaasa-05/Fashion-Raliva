<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use App\Models\WarehouseStaff;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManajemenPenggunaController extends Controller
{
    private function routeForRole(string $roleName): string
    {
        return match ($roleName) {
            Role::SUPER_ADMIN => route('superadmin.manajemen-pengguna'),
            Role::ADMIN => route('admin.dashboard'),
            Role::PRODUKSI => route('produksi.dashboard'),
            Role::GUDANG => route('gudang.dashboard'),
            Role::OWNER => route('owner.dashboard'),
            default => route('customer.account'),
        };
    }

    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nomor_telepon', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $role = Role::where('nama_role', $request->role)->first();
            if ($role) {
                $query->where('role_id', $role->role_id);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('nama_lengkap')->paginate(20)->withQueryString();
        $roles = Role::where('status', 'aktif')->get();

        $stats = [
            'total' => User::count(),
            'aktif' => User::where('status', User::STATUS_AKTIF)->count(),
            'nonaktif' => User::where('status', User::STATUS_NONAKTIF)->count(),
            'suspend' => User::where('status', User::STATUS_SUSPEND)->count(),
        ];

        return view('SuperAdmin.manajemen-pengguna.index', [
            'users' => $users,
            'roles' => $roles,
            'stats' => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,role_id',
            'nomor_telepon' => 'nullable|string|max:30',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 150 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role_id.required' => 'Peran wajib dipilih.',
            'role_id.exists' => 'Peran tidak ditemukan.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        $user = User::create($validated);

        $roleName = Role::find($validated['role_id'])->nama_role;

        ActivityLogger::log(
            'user.create',
            User::class,
            $user->user_id,
            [],
            $user->only(['nama_lengkap', 'email', 'role_id', 'status']),
            sprintf('Menambahkan pengguna baru "%s" dengan peran "%s".', $user->nama_lengkap, $roleName)
        );

        if ($user->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $user->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Akun Dibuat',
                'pesan' => sprintf('Akun Anda "%s" telah dibuat dengan peran "%s".', $user->nama_lengkap, $roleName),
                'url' => $this->routeForRole($roleName),
            ]);
        }

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Pengguna Ditambahkan', sprintf('Pengguna "%s" (peran %s) ditambahkan.', $user->nama_lengkap, $roleName), route('superadmin.manajemen-pengguna'));

        return back()->with('toast', [
            'message' => 'Pengguna "'.$user->nama_lengkap.'" berhasil ditambahkan.',
            'icon' => 'task_alt',
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,'.$user->user_id.',user_id',
            'role_id' => 'required|exists:roles,role_id',
            'nomor_telepon' => 'nullable|string|max:30',
            'status' => 'required|in:aktif,nonaktif,suspend',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 150 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan pengguna lain.',
            'role_id.required' => 'Peran wajib dipilih.',
            'role_id.exists' => 'Peran tidak ditemukan.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $lama = $user->only(['nama_lengkap', 'email', 'role_id', 'nomor_telepon', 'status']);
        $user->update($validated);

        $roleName = Role::find($validated['role_id'])->nama_role;

        ActivityLogger::log(
            'user.update',
            User::class,
            $user->user_id,
            $lama,
            $user->only(['nama_lengkap', 'email', 'role_id', 'nomor_telepon', 'status']),
            sprintf('Memperbarui data pengguna "%s".', $user->nama_lengkap)
        );

        $roleAkhir = $roleName;

        if ((int) $user->user_id !== (int) auth()->id()) {
            Notification::create([
                'user_id' => $user->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Akun Diperbarui',
                'pesan' => sprintf('Data akun Anda telah diperbarui oleh Super Admin (peran kini: "%s").', $roleAkhir),
                'url' => $this->routeForRole($roleName),
            ]);
        }

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Pengguna Diperbarui', sprintf('Data pengguna "%s" diperbarui.', $user->nama_lengkap), route('superadmin.manajemen-pengguna'));

        return back()->with('toast', [
            'message' => 'Data "'.$user->nama_lengkap.'" berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function destroy(User $user)
    {
        if ((int) $user->user_id === 1) {
            return back()->with('toast', [
                'message' => 'Tidak dapat menghapus akun Super Admin utama.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $nama = $user->nama_lengkap;
        $roleName = $user->role->nama_role ?? '-';

        $user->delete();

        ActivityLogger::log(
            'user.delete',
            User::class,
            $user->user_id,
            ['nama_lengkap' => $nama, 'role' => $roleName],
            [],
            sprintf('Menghapus pengguna "%s" (peran: %s).', $nama, $roleName)
        );

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Pengguna Dihapus', sprintf('Pengguna "%s" (peran %s) dihapus.', $nama, $roleName), route('superadmin.manajemen-pengguna'));

        return back()->with('toast', [
            'message' => 'Pengguna "'.$nama.'" berhasil dihapus.',
            'icon' => 'task_alt',
        ]);
    }

    public function getDetail(User $user)
    {
        $user->load('role', 'ownedStores.storeStaff.user.role');

        $roleNama = $user->role?->nama_role;
        $isOwner = $roleNama === Role::OWNER;
        $isStaff = in_array($roleNama, [Role::ADMIN, Role::PRODUKSI, Role::GUDANG], true);

        $aktivitas = ActivityLog::where('user_id', $user->user_id)
            ->orderByDesc('activity_log_id')
            ->limit(10)
            ->get()
            ->map(fn ($log) => [
                'deskripsi' => $log->deskripsi,
                'tanggal' => $log->created_at->translatedFormat('d M Y, H:i'),
            ]);

        $toko = [];
        if ($isOwner) {
            $toko = $user->ownedStores->map(fn ($store) => [
                'store_id' => $store->store_id,
                'nama' => $store->nama_toko,
                'produk' => $store->products()->count(),
                'rating' => round($store->reviews()->avg('rating') ?? 0, 1),
                'status' => $store->status,
                'karyawan' => $store->storeStaff->map(fn (StoreStaff $st) => [
                    'user_id' => $st->user?->user_id ?? $st->user_id,
                    'nama' => $st->user?->nama_lengkap ?? '-',
                    'email' => $st->user?->email ?? '',
                    'role' => $st->user?->role?->nama_role ?? '-',
                    'role_id' => $st->user?->role_id,
                    'status' => $st->status,
                    'foto' => $st->user?->foto_profil_url,
                    'initial' => strtoupper(mb_substr($st->user?->nama_lengkap ?? '?', 0, 2)),
                ])->values()->all(),
            ]);
        }

        $penugasan = [];
        $warehouses = [];
        if ($isStaff) {
            $penugasan = StoreStaff::where('user_id', $user->user_id)
                ->with('store.owner')
                ->orderByDesc('store_staff_id')
                ->get()
                ->map(fn (StoreStaff $s) => [
                    'store_id' => $s->store_id,
                    'nama_toko' => $s->store?->nama_toko ?? '-',
                    'status_toko' => $s->store?->status ?? '-',
                    'owner_user_id' => $s->store?->owner_id ?? $s->store?->owner?->user_id,
                    'owner_nama' => $s->store?->owner?->nama_lengkap ?? '-',
                    'owner_email' => $s->store?->owner?->email ?? '',
                    'tanggal_penugasan' => $s->tanggal_penugasan?->translatedFormat('d M Y'),
                    'status_penugasan' => $s->status,
                ])->values()->all();

            $warehouses = WarehouseStaff::where('user_id', $user->user_id)
                ->with('warehouse.store.owner')
                ->orderByDesc('warehouse_staff_id')
                ->get()
                ->map(fn ($ws) => [
                    'gudang' => $ws->warehouse?->nama_gudang ?? '-',
                    'store' => $ws->warehouse?->store?->nama_toko ?? '-',
                    'owner' => $ws->warehouse?->store?->owner?->nama_lengkap ?? '-',
                    'status' => $ws->status,
                    'tanggal' => $ws->tanggal_penugasan?->translatedFormat('d M Y'),
                ])->values()->all();
        }

        return response()->json([
            'user_id' => $user->user_id,
            'nama' => $user->nama_lengkap,
            'email' => $user->email,
            'nomor_telepon' => $user->nomor_telepon,
            'role' => $user->role->nama_role ?? '-',
            'role_id' => $user->role_id,
            'status' => $user->status,
            'is_owner' => $isOwner,
            'show_toko' => $isOwner,
            'is_staff' => $isStaff,
            'show_penugasan' => $isStaff,
            'penugasan' => $penugasan,
            'warehouses' => $warehouses,
            'is_super_admin' => $user->role && $user->role->nama_role === Role::SUPER_ADMIN,
            'email_verified_at' => $user->email_verified_at?->toISOString(),
            'is_verified' => $user->email_verified_at !== null,
            'foto_profil_url' => $user->foto_profil_url,
            'initial' => strtoupper(mb_substr($user->nama_lengkap, 0, 2)),
            'toko' => $toko,
            'aktivitas' => $aktivitas,
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role_id' => 'required|exists:roles,role_id',
        ], [
            'role_id.required' => 'Peran wajib dipilih.',
            'role_id.exists' => 'Peran tidak ditemukan.',
        ]);

        $lama = $user->only(['role_id']);
        $roleBaru = Role::find($data['role_id']);

        $user->update(['role_id' => $data['role_id']]);

        ActivityLogger::log(
            'user.role.update',
            User::class,
            $user->user_id,
            ['role_id' => $lama['role_id']],
            ['role_id' => $data['role_id']],
            sprintf('Mengubah peran "%s" menjadi "%s".', $user->nama_lengkap, $roleBaru->nama_role)
        );

        if ((int) $user->user_id !== (int) auth()->id()) {
            Notification::create([
                'user_id' => $user->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Peran Akun Diubah',
                'pesan' => sprintf('Peran akun Anda diubah menjadi "%s".', $roleBaru->nama_role),
                'url' => $this->routeForRole($roleBaru->nama_role),
            ]);
        }

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Peran Pengguna Diubah', sprintf('Peran "%s" menjadi %s.', $user->nama_lengkap, $roleBaru->nama_role), route('superadmin.manajemen-pengguna'));

        return back()->with('toast', [
            'message' => 'Peran "'.$user->nama_lengkap.'" berhasil diubah menjadi '.$roleBaru->nama_role.'.',
            'icon' => 'task_alt',
        ]);
    }

    public function nonaktifkan(User $user)
    {
        if ((int) $user->user_id === 1) {
            return back()->with('toast', [
                'message' => 'Tidak dapat menonaktifkan akun Super Admin utama.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if (! in_array($user->status, [User::STATUS_AKTIF, User::STATUS_NONAKTIF], true)) {
            return back()->with('toast', [
                'message' => 'Hanya pengguna berstatus aktif atau nonaktif yang dapat diubah melalui tombol ini.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $cascade = DB::transaction(function () use ($user) {
            $locked = User::whereKey($user->user_id)->lockForUpdate()->first();

            if (! $locked || ! in_array($locked->status, [User::STATUS_AKTIF, User::STATUS_NONAKTIF], true)) {
                return back()->with('toast', [
                    'message' => 'Status pengguna sudah berubah oleh pihak lain.',
                    'icon' => 'gpp_maybe',
                ]);
            }

            $baru = $locked->status === User::STATUS_AKTIF ? User::STATUS_NONAKTIF : User::STATUS_AKTIF;
            $lama = $locked->status;

            $locked->update(['status' => $baru]);

            ActivityLogger::log(
                'user.status.update',
                User::class,
                $locked->user_id,
                ['status' => $lama],
                ['status' => $baru],
                sprintf('Mengubah status "%s" dari %s menjadi %s.', $locked->nama_lengkap, $lama, $baru)
            );

            if ((int) $locked->user_id !== (int) auth()->id()) {
                Notification::create([
                    'user_id' => $locked->user_id,
                    'aktor_id' => ActivityLogger::resolveActorId(),
                    'tipe' => Notification::TIPE_SISTEM,
                    'judul' => 'Status Akun Diubah',
                    'pesan' => sprintf('Status akun Anda diubah menjadi %s.', $baru === User::STATUS_AKTIF ? 'aktif' : 'nonaktif'),
                    'url' => route('customer.account'),
                ]);
            }

            if (! $locked->role || $locked->role->nama_role !== Role::OWNER) {
                Notification::fireSelf(Notification::TIPE_SISTEM, 'Status Pengguna Diubah', sprintf('Status "%s" menjadi %s.', $locked->nama_lengkap, $baru), route('superadmin.manajemen-pengguna'));

                return [
                    'status' => $baru,
                    'toko' => 0,
                    'staff' => 0,
                ];
            }

            $tokoCount = Store::where('owner_id', $locked->user_id)
                ->whereIn('status', [Store::STATUS_AKTIF, Store::STATUS_NONAKTIF])
                ->update(['status' => $baru]);

            StoreStaff::whereHas('store', fn ($q) => $q->where('owner_id', $locked->user_id))
                ->whereIn('status', [StoreStaff::STATUS_AKTIF, StoreStaff::STATUS_NONAKTIF])
                ->update(['status' => $baru]);

            $staffUsers = User::whereHas('storeAssignments.store', fn ($q) => $q->where('owner_id', $locked->user_id))
                ->where('user_id', '!=', $locked->user_id)
                ->whereIn('status', [User::STATUS_AKTIF, User::STATUS_NONAKTIF])
                ->get();

            $staffCount = $staffUsers->count();

            if ($staffUsers->isNotEmpty()) {
                User::whereIn('user_id', $staffUsers->pluck('user_id'))
                    ->update(['status' => $baru]);
            }

            if ($tokoCount > 0 || $staffCount > 0) {
                ActivityLogger::log(
                    'owner.cascade.update',
                    User::class,
                    $locked->user_id,
                    [],
                    ['stores' => $tokoCount, 'staff' => $staffCount, 'status' => $baru],
                    sprintf('Cascade %s: %d toko, %d staff turut di%s.', $locked->nama_lengkap, $tokoCount, $staffCount, $baru === 'aktif' ? 'aktifkan' : 'nonaktifkan')
                );
            }

            Notification::fireSelf(Notification::TIPE_SISTEM, 'Status & Cascade Diubah', sprintf('Status "%s" menjadi %s (cascade %d staff, %d toko).', $locked->nama_lengkap, $baru, $staffCount, $tokoCount), route('superadmin.manajemen-pengguna'));

            return [
                'status' => $baru,
                'toko' => $tokoCount,
                'staff' => $staffCount,
            ];
        });

        if (! is_array($cascade)) {
            return $cascade;
        }

        if ($cascade['toko'] > 0 || $cascade['staff'] > 0) {
            return back()->with('toast', [
                'message' => 'Status "'.$user->nama_lengkap.'" berhasil diubah menjadi '.$cascade['status'].' (+'.$cascade['staff'].' staff & '.$cascade['toko'].' toko turut di'.$cascade['status'].').',
                'icon' => 'task_alt',
            ]);
        }

        return back()->with('toast', [
            'message' => 'Status "'.$user->nama_lengkap.'" berhasil diubah menjadi '.$cascade['status'].'.',
            'icon' => 'task_alt',
        ]);
    }
}
