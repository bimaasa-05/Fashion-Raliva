<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class RiwayatAktivitasController extends Controller
{
    public function index()
    {
        $query = ActivityLog::with('user:user_id,nama_lengkap')
            ->orderByDesc('activity_log_id');

        if ($kategori = request('kategori')) {
            $prefixMap = [
                'pengguna' => 'user.',
                'toko' => 'store.',
                'produk' => 'product.',
                'keuangan' => ['order.', 'withdrawal.', 'refund.', 'commission.', 'wallet.'],
                'sistem' => ['setting.', 'system.', 'commission.update'],
            ];
            $prefix = $prefixMap[$kategori] ?? null;
            if ($prefix) {
                if (is_array($prefix)) {
                    $query->where(function ($q) use ($prefix) {
                        foreach ($prefix as $p) {
                            $q->orWhere('aksi', 'like', $p.'%');
                        }
                    });
                } else {
                    $query->where('aksi', 'like', $prefix.'%');
                }
            }
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('SuperAdmin.riwayat-aktivitas.index', [
            'logs' => $logs,
        ]);
    }
}
