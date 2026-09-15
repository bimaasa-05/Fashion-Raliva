<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\PermintaanOperasional;
use App\Models\Role;
use App\Services\NotificationService;
use App\Support\AdminContext;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanOperasionalController extends Controller
{
    public function index(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $status = $request->query('status', 'semua');

        $query = PermintaanOperasional::with(['store:store_id,nama_toko', 'pemohon:user_id,nama_lengkap,role_id', 'pemohon.role:role_id,nama_role', 'admin:user_id,nama_lengkap'])
            ->whereIn('store_id', $storeIds)
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at');

        if ($status !== 'semua' && in_array($status, ['pending', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        $permintaan = $query->paginate(20)->withQueryString();

        $stats = [
            'pending' => PermintaanOperasional::whereIn('store_id', $storeIds)->where('status', 'pending')->count(),
            'disetujui' => PermintaanOperasional::whereIn('store_id', $storeIds)->where('status', 'disetujui')->count(),
            'ditolak' => PermintaanOperasional::whereIn('store_id', $storeIds)->where('status', 'ditolak')->count(),
        ];

        return view('Admin.permintaan-operasional.index', [
            'permintaan' => $permintaan,
            'stats' => $stats,
            'activeStatus' => $status,
        ]);
    }

    public function show(PermintaanOperasional $permintaan)
    {
        if (! AdminContext::canAccessStore($permintaan->store_id)) {
            return back()->with('toast', ['message' => 'Permintaan di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        $permintaan->load(['store:store_id,nama_toko', 'pemohon:user_id,nama_lengkap,role_id', 'admin:user_id,nama_lengkap', 'pemohon.role:role_id,nama_role']);
        return view('Admin.permintaan-operasional.show', compact('permintaan'));
    }

    public function setujui(Request $request, PermintaanOperasional $permintaan)
    {
        if (! AdminContext::canAccessStore($permintaan->store_id)) {
            return back()->with('toast', ['message' => 'Permintaan di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        if ($permintaan->status !== PermintaanOperasional::STATUS_PENDING) {
            return back()->with('toast', ['message' => 'Permintaan ini sudah diproses.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($permintaan, $data) {
            $permintaan->update([
                'status' => PermintaanOperasional::STATUS_DISETUJUI,
                'admin_id' => ActivityLogger::resolveActorId(),
                'catatan_admin' => $data['catatan'] ?? null,
                'diproses_pada' => now(),
            ]);

            ActivityLogger::log(
                'admin.permintaan.setujui',
                PermintaanOperasional::class,
                $permintaan->permintaan_id,
                ['status' => 'pending'],
                ['status' => 'disetujui'],
                sprintf('Menyetujui permintaan operasional "%s".', $permintaan->judul)
            );
        });

        Notification::create([
            'user_id' => $permintaan->pemohon_id,
            'aktor_id' => ActivityLogger::resolveActorId(),
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => 'Permintaan Disetujui',
            'pesan' => sprintf('Permintaan "%s" telah disetujui oleh Admin.', $permintaan->judul),
            'url' => route('admin.permintaan-operasional.show', $permintaan->permintaan_id),
        ]);

        return back()->with('toast', ['message' => 'Permintaan disetujui.', 'icon' => 'task_alt']);
    }

    public function tolak(Request $request, PermintaanOperasional $permintaan)
    {
        if (! AdminContext::canAccessStore($permintaan->store_id)) {
            return back()->with('toast', ['message' => 'Permintaan di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        if ($permintaan->status !== PermintaanOperasional::STATUS_PENDING) {
            return back()->with('toast', ['message' => 'Permintaan ini sudah diproses.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:500',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        DB::transaction(function () use ($permintaan, $data) {
            $permintaan->update([
                'status' => PermintaanOperasional::STATUS_DITOLAK,
                'admin_id' => ActivityLogger::resolveActorId(),
                'catatan_admin' => $data['alasan'],
                'diproses_pada' => now(),
            ]);

            ActivityLogger::log(
                'admin.permintaan.tolak',
                PermintaanOperasional::class,
                $permintaan->permintaan_id,
                ['status' => 'pending'],
                ['status' => 'ditolak'],
                sprintf('Menolak permintaan operasional "%s". Alasan: %s', $permintaan->judul, $data['alasan'])
            );
        });

        Notification::create([
            'user_id' => $permintaan->pemohon_id,
            'aktor_id' => ActivityLogger::resolveActorId(),
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => 'Permintaan Ditolak',
            'pesan' => sprintf('Permintaan "%s" ditolak. Alasan: %s', $permintaan->judul, $data['alasan']),
            'url' => route('admin.permintaan-operasional.show', $permintaan->permintaan_id),
        ]);

        return back()->with('toast', ['message' => 'Permintaan ditolak.', 'icon' => 'block']);
    }
}