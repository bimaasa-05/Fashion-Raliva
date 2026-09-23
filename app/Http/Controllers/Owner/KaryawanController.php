<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Models\User;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public const ROLE_KARYAWAN = ['admin', 'produksi', 'gudang'];

    public const ROLE_NAMA = [
        'admin' => Role::ADMIN,
        'produksi' => Role::PRODUKSI,
        'gudang' => Role::GUDANG,
    ];

    public static function roleOf($staff): string
    {
        $nama = $staff->user?->role?->nama_role;
        $key = array_search($nama, self::ROLE_NAMA, true);

        return $key !== false ? $key : 'lainnya';
    }

    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $staff = StoreStaff::with('user.role')
            ->where('store_id', $storeId)
            ->orderByDesc('store_staff_id')
            ->paginate(15)
            ->withQueryString();

        $all = StoreStaff::with('user.role')->where('store_id', $storeId)->get();
        $summary = [
            'total' => $all->count(),
            'admin' => $all->filter(fn($s) => static::roleOf($s) === 'admin')->count(),
            'produksi_gudang' => $all->filter(fn($s) => in_array(static::roleOf($s), ['produksi', 'gudang'], true))->count(),
            'nonaktif' => $all->where('status', 'nonaktif')->count(),
        ];

        $roleLabel = [
            'admin' => 'Admin Toko',
            'produksi' => 'Produksi',
            'gudang' => 'Gudang',
        ];

        $storeName = \App\Support\OwnerContext::currentStore()?->nama_toko ?? '-';

        return view('Owner.karyawan.index', compact('staff', 'summary', 'roleLabel', 'storeName'));
    }

    public function store(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,produksi,gudang'],
        ]);

        $roleId = Role::where('nama_role', self::ROLE_NAMA[$validated['role']])->value('role_id');
        if (! $roleId) {
            return back()->with('error', 'Role karyawan tidak tersedia.')->withInput();
        }

        $user = User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'nomor_telepon' => $validated['nomor_telepon'] ?? null,
            'password' => Hash::make($validated['password']),
            'role_id' => $roleId,
            'status' => User::STATUS_AKTIF,
            'email_verified_at' => now(),
        ]);

        StoreStaff::create([
            'store_id' => $storeId,
            'user_id' => $user->user_id,
            'tanggal_penugasan' => now(),
            'status' => 'aktif',
        ]);

        $urlStaff = match ($validated['role']) {
            'admin' => route('admin.dashboard'),
            'produksi' => route('produksi.dashboard'),
            default => route('gudang.dashboard'),
        };
        Notification::create([
            'user_id' => $user->user_id,
            'aktor_id' => auth()->id(),
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => 'Karyawan Ditambahkan',
            'pesan' => sprintf('Anda ditugaskan sebagai %s di toko %s oleh Owner.', ucfirst($validated['role']), OwnerContext::currentStore()?->nama_toko ?? '-'),
            'url' => $urlStaff,
        ]);
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Karyawan Ditambahkan', sprintf('Karyawan "%s" (%s) berhasil ditambahkan.', $validated['nama_lengkap'], $validated['role']), route('owner.karyawan'));

        return redirect()->route('owner.karyawan')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, StoreStaff $storeStaff)
    {
        if ($storeStaff->store_id !== OwnerContext::firstStoreId()) {
            abort(403);
        }

        $validated = $request->validate([
            'role' => ['required', 'in:admin,produksi,gudang'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $roleId = Role::where('nama_role', self::ROLE_NAMA[$validated['role']])->value('role_id');
        if (! $roleId) {
            return back()->with('error', 'Role karyawan tidak tersedia.');
        }
        if ($storeStaff->user) {
            $storeStaff->user->update(['role_id' => $roleId]);
        }
        $storeStaff->update(['status' => $validated['status']]);

        $urlStaff = match ($validated['role']) {
            'admin' => route('admin.dashboard'),
            'produksi' => route('produksi.dashboard'),
            default => route('gudang.dashboard'),
        };
        if ($storeStaff->user_id) {
            Notification::create([
                'user_id' => $storeStaff->user_id,
                'aktor_id' => auth()->id(),
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Akun Karyawan Diubah',
                'pesan' => sprintf('Role Anda kini %s dan status %s.', ucfirst($validated['role']), $validated['status']),
                'url' => $urlStaff,
            ]);
        }
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Data Karyawan Diperbarui', sprintf('Data karyawan "%s" diperbarui (role %s, status %s).', $storeStaff->user?->nama_lengkap ?? '-', $validated['role'], $validated['status']), route('owner.karyawan'));

        return redirect()->route('owner.karyawan')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(StoreStaff $storeStaff)
    {
        if ($storeStaff->store_id !== OwnerContext::firstStoreId()) {
            abort(403);
        }

        // Soft: nonaktifkan penugasan (jangan hapus user agar aman).
        $storeStaff->update(['status' => 'nonaktif']);

        if ($storeStaff->user_id) {
            Notification::create([
                'user_id' => $storeStaff->user_id,
                'aktor_id' => auth()->id(),
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Penugasan Dinonaktifkan',
                'pesan' => sprintf('Penugasan Anda di toko %s dinonaktifkan oleh Owner.', OwnerContext::currentStore()?->nama_toko ?? '-'),
                'url' => route('customer.account'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_SISTEM, 'Karyawan Dinonaktifkan', sprintf('Karyawan "%s" dinonaktifkan.', $storeStaff->user?->nama_lengkap ?? '-'), route('owner.karyawan'));

        return redirect()->route('owner.karyawan')->with('success', 'Karyawan dinonaktifkan.');
    }
}
