<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
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

        if ($user && $user->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $user->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Ditugaskan ke Toko',
                'pesan' => sprintf('Anda ditugaskan sebagai staff di toko "%s".', $store?->nama_toko ?? '-'),
                'url' => $this->staffRoute((int) ($user->role_id ?? 0)),
            ]);
        }

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Staff Ditugaskan', sprintf('"%s" ditugaskan ke toko "%s".', $user?->nama_lengkap ?? '-', $store?->nama_toko ?? '-'), route('superadmin.store-staff'));

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

        if ($staff->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $staff->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Status Penugasan Diubah',
                'pesan' => sprintf('Status keanggotaan Anda di toko "%s" menjadi "%s".', $staff->store->nama_toko ?? '-', $validated['status']),
                'url' => $this->staffRoute((int) ($staff->user?->role_id ?? 0)),
            ]);
        }

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Status Staff Diubah', sprintf('Status "%s" di toko "%s" menjadi "%s".', $staff->user->nama_lengkap ?? '-', $staff->store->nama_toko ?? '-', $validated['status']), route('superadmin.store-staff'));

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

        if ($staff->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $staff->user_id,
                'aktor_id' => ActivityLogger::resolveActorId(),
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => 'Penugasan Dinonaktifkan',
                'pesan' => sprintf('Penugasan Anda di toko "%s" dinonaktifkan.', $staff->store->nama_toko ?? '-'),
                'url' => $this->staffRoute((int) ($staff->user?->role_id ?? 0)),
            ]);
        }

        Notification::fireSelf(Notification::TIPE_SISTEM, 'Staff Dinonaktifkan', sprintf('Penugasan "%s" di toko "%s" dinonaktifkan.', $staff->user->nama_lengkap ?? '-', $staff->store->nama_toko ?? '-'), route('superadmin.store-staff'));

        return back()->with('toast', [
            'message' => 'Staff berhasil dinonaktifkan.',
            'icon' => 'task_alt',
        ]);
    }

    private function staffRoute(int $roleId): string
    {
        return match ($roleId) {
            3 => route('admin.dashboard'),
            5 => route('gudang.dashboard'),
            4 => route('produksi.dashboard'),
            default => route('admin.dashboard'),
        };
    }
}
