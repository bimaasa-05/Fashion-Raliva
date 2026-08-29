<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManajemenPenggunaController extends Controller
{
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

        $users = $query->orderBy('nama_lengkap')->get();
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

        return back()->with('toast', [
            'message' => 'Pengguna "'.$nama.'" berhasil dihapus.',
            'icon' => 'task_alt',
        ]);
    }

    public function getDetail(User $user)
    {
        $user->load('role', 'ownedStores');

        $isSuperAdmin = $user->role && $user->role->nama_role === Role::SUPER_ADMIN;

        $aktivitas = ActivityLog::where('user_id', $user->user_id)
            ->orderByDesc('activity_log_id')
            ->limit(10)
            ->get()
            ->map(fn ($log) => [
                'deskripsi' => $log->deskripsi,
                'tanggal' => $log->created_at->translatedFormat('d M Y, H:i'),
            ]);

        $toko = [];
        if (! $isSuperAdmin) {
            $toko = $user->ownedStores->map(fn ($store) => [
                'nama' => $store->nama_toko,
                'produk' => $store->products()->count(),
                'rating' => round($store->reviews()->avg('rating') ?? 0, 1),
            ]);
        }

        return response()->json([
            'user_id' => $user->user_id,
            'nama' => $user->nama_lengkap,
            'email' => $user->email,
            'nomor_telepon' => $user->nomor_telepon,
            'role' => $user->role->nama_role ?? '-',
            'role_id' => $user->role_id,
            'status' => $user->status,
            'is_super_admin' => $isSuperAdmin,
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

        $baru = $user->status === User::STATUS_AKTIF ? User::STATUS_NONAKTIF : User::STATUS_AKTIF;
        $lama = $user->status;

        $user->update(['status' => $baru]);

        ActivityLogger::log(
            'user.status.update',
            User::class,
            $user->user_id,
            ['status' => $lama],
            ['status' => $baru],
            sprintf('Mengubah status "%s" dari %s menjadi %s.', $user->nama_lengkap, $lama, $baru)
        );

        return back()->with('toast', [
            'message' => 'Status "'.$user->nama_lengkap.'" berhasil diubah menjadi '.$baru.'.',
            'icon' => 'task_alt',
        ]);
    }
}
