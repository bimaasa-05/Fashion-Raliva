<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Notification;
use App\Models\PermintaanOperasional;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanOperasionalController extends Controller
{
    public function index(Request $request)
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        $status = $request->query('status', 'semua');

        $query = PermintaanOperasional::with(['store:store_id,nama_toko', 'admin:user_id,nama_lengkap'])
            ->where('pemohon_id', auth()->id())
            ->orderByDesc('created_at');

        if ($status !== 'semua' && in_array($status, ['pending', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        $permintaan = $query->paginate(15)->withQueryString();

        $stats = [
            'pending' => PermintaanOperasional::where('pemohon_id', auth()->id())->where('status', 'pending')->count(),
            'disetujui' => PermintaanOperasional::where('pemohon_id', auth()->id())->where('status', 'disetujui')->count(),
            'ditolak' => PermintaanOperasional::where('pemohon_id', auth()->id())->where('status', 'ditolak')->count(),
        ];

        $jenisOptions = PermintaanOperasional::JENIS;

        // Detect role from route for view namespace
        $role = $request->segment(1); // 'produksi', 'gudang', etc.

        return view(ucfirst($role) . '.permintaan.index', [
            'permintaan' => $permintaan,
            'stats' => $stats,
            'activeStatus' => $status,
            'jenisOptions' => $jenisOptions,
        ]);
    }

    public function store(Request $request)
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        $storeId = $storeIds[0] ?? null;
        if (! $storeId) {
            return back()->with('toast', ['message' => 'Anda belum ditugaskan ke toko mana pun.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'jenis_permintaan' => ['required', 'in:' . implode(',', array_keys(PermintaanOperasional::JENIS))],
            'judul' => 'required|string|max:150',
            'deskripsi' => 'required|string|max:2000',
            'payload' => 'nullable|array',
        ], [
            'jenis_permintaan.required' => 'Jenis permintaan wajib dipilih.',
            'judul.required' => 'Judul wajib diisi.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
        ]);

        $permintaan = DB::transaction(function () use ($storeId, $data) {
            $p = PermintaanOperasional::create([
                'store_id' => $storeId,
                'pemohon_id' => auth()->id(),
                'jenis_permintaan' => $data['jenis_permintaan'],
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'],
                'payload' => $data['payload'] ?? null,
                'status' => PermintaanOperasional::STATUS_PENDING,
            ]);

            ActivityLogger::log(
                'permintaan.create',
                PermintaanOperasional::class,
                $p->permintaan_id,
                null,
                ['judul' => $p->judul, 'jenis' => $p->jenis_permintaan],
                sprintf('Mengajukan permintaan operasional "%s" (%s).', $p->judul, $p->jenis_permintaan)
            );

            return $p;
        });

        // Notify Admin
        NotificationService::sendToRole(Role::ADMIN, Notification::TIPE_SISTEM,
            'Permintaan Operasional Baru',
            sprintf('Permintaan "%s" dari %s menunggu persetujuan.', $permintaan->judul, auth()->user()->nama_lengkap),
            ActivityLogger::resolveActorId(),
            route('admin.permintaan-operasional')
        );

        return back()->with('toast', ['message' => 'Permintaan berhasil diajukan.', 'icon' => 'task_alt']);
    }
}