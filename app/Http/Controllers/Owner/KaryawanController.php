<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
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

        return redirect()->route('owner.karyawan')->with('success', 'Karyawan berhasil ditambahkan.');
    }
}
