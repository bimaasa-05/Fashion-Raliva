<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\StoreStaff;
use App\Models\User;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public const ROLE_MAP = [
        3 => 'admin',
        4 => 'produksi',
        5 => 'gudang',
    ];

    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $staff = StoreStaff::with('user')
            ->where('store_id', $storeId)
            ->orderByDesc('store_staff_id')
            ->paginate(15)
            ->withQueryString();

        $all = StoreStaff::with('user')->where('store_id', $storeId)->get();
        $roleOf = fn($s) => self::ROLE_MAP[$s->user?->role_id] ?? 'lainnya';
        $summary = [
            'total' => $all->count(),
            'admin' => $all->filter(fn($s) => ($s->user?->role_id ?? 0) === 3)->count(),
            'produksi_gudang' => $all->filter(fn($s) => in_array($s->user?->role_id, [4, 5]))->count(),
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
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,produksi,gudang'],
        ]);

        $roleId = array_search($validated['role'], self::ROLE_MAP);

        $user = User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $roleId,
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

        $roleId = array_search($validated['role'], self::ROLE_MAP);
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
