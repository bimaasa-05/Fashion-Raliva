<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreStaff;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class StoreStaffController extends Controller
{
    public function index()
    {
        $staff = StoreStaff::with([
            'user:user_id,nama_lengkap,email,role_id',
            'store:store_id,nama_toko',
        ])->orderByDesc('store_staff_id')->get();

        $roleLabel = [
            3 => 'Admin Toko',
            4 => 'Produksi',
            5 => 'Gudang',
        ];

        $roleOf = fn ($s) => $roleLabel[$s->user?->role_id] ?? 'Lainnya';
        $stores = Store::orderBy('nama_toko')->get();

        $users = User::whereIn('role_id', [3, 4, 5])
            ->where('status', User::STATUS_AKTIF)
            ->orderBy('nama_lengkap')
            ->get();

        $summary = [
            'total' => $staff->count(),
            'aktif' => $staff->where('status', StoreStaff::STATUS_AKTIF)->count(),
            'nonaktif' => $staff->where('status', StoreStaff::STATUS_NONAKTIF)->count(),
            'total_toko' => $staff->pluck('store_id')->unique()->count(),
        ];

        $staffJson = $staff->map(fn ($s) => [
            'id' => $s->store_staff_id,
            'nama' => $s->user->nama_lengkap ?? '-',
            'email' => $s->user->email ?? '-',
            'role' => $s->user->role_id ?? 0,
            'role_label' => $roleOf($s),
            'toko' => $s->store->nama_toko ?? '-',
            'store_id' => $s->store_id,
            'tanggal' => $s->tanggal_penugasan?->translatedFormat('d M Y') ?? '-',
            'status' => $s->status,
        ])->values()->all();

        return view('SuperAdmin.store-staff.index', compact('staff', 'stores', 'users', 'roleLabel', 'roleOf', 'summary', 'staffJson'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => ['required', 'exists:stores,store_id'],
            'user_id' => ['required', 'exists:users,user_id'],
        ]);

        $exists = StoreStaff::where('store_id', $validated['store_id'])
            ->where('user_id', $validated['user_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'user_id' => 'User ini sudah ditugaskan di toko tersebut.',
            ]);
        }

        $staff = StoreStaff::create([
            'store_id' => $validated['store_id'],
            'user_id' => $validated['user_id'],
            'tanggal_penugasan' => now(),
            'status' => StoreStaff::STATUS_AKTIF,
        ]);

        $user = User::find($validated['user_id']);
        $store = Store::find($validated['store_id']);

        ActivityLogger::log(
            'store_staff.assign',
            StoreStaff::class,
            $staff->store_staff_id,
            null,
            $staff->toArray(),
            sprintf('Menugaskan "%s" ke toko "%s".', $user?->nama_lengkap ?? '-', $store?->nama_toko ?? '-')
        );

        return back()->with('toast', [
            'message' => 'Staff berhasil ditugaskan ke toko.',
            'icon' => 'task_alt',
        ]);
    }

    public function show(StoreStaff $staff)
    {
        $staff->load([
            'user:user_id,nama_lengkap,email,role_id',
            'store:store_id,nama_toko',
        ]);

        $roleLabel = [
            3 => 'Admin Toko',
            4 => 'Produksi',
            5 => 'Gudang',
        ];

        return response()->json([
            'staff_id' => $staff->store_staff_id,
            'nama' => $staff->user->nama_lengkap ?? '-',
            'email' => $staff->user->email ?? '-',
            'role' => $roleLabel[$staff->user->role_id] ?? 'Lainnya',
            'toko' => $staff->store->nama_toko ?? '-',
            'tanggal_penugasan' => $staff->tanggal_penugasan?->translatedFormat('d M Y') ?? '-',
            'status' => $staff->status,
        ]);
    }

    public function update(Request $request, StoreStaff $staff)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $old = $staff->toArray();

        $staff->update(['status' => $validated['status']]);

        ActivityLogger::log(
            'store_staff.update',
            StoreStaff::class,
            $staff->store_staff_id,
            $old,
            $staff->toArray(),
            sprintf('Mengubah status staff "%s" di toko "%s" menjadi "%s".', $staff->user->nama_lengkap ?? '-', $staff->store->nama_toko ?? '-', $validated['status'])
        );

        return back()->with('toast', [
            'message' => 'Status staff berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    public function destroy(StoreStaff $staff)
    {
        $old = $staff->toArray();

        $staff->update(['status' => StoreStaff::STATUS_NONAKTIF]);

        ActivityLogger::log(
            'store_staff.deactivate',
            StoreStaff::class,
            $staff->store_staff_id,
            $old,
            $staff->toArray(),
            sprintf('Menonaktifkan staff "%s" dari toko "%s".', $staff->user->nama_lengkap ?? '-', $staff->store->nama_toko ?? '-')
        );

        return back()->with('toast', [
            'message' => 'Staff berhasil dinonaktifkan.',
            'icon' => 'task_alt',
        ]);
    }
}
