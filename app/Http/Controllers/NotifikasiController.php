<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    protected const ICON_MAP = [
        Notification::TIPE_ORDER => 'shopping_bag',
        Notification::TIPE_PEMBAYARAN => 'payments',
        Notification::TIPE_PENGIRIMAN => 'local_shipping',
        Notification::TIPE_KOMPLAIN => 'support_agent',
        Notification::TIPE_WALLET => 'account_balance_wallet',
        Notification::TIPE_PROMO => 'local_offer',
        Notification::TIPE_SISTEM => 'notifications',
    ];

    public function getNotif(): JsonResponse
    {
        $user = Auth::user();

        $notifications = Notification::with('aktor:user_id,nama_lengkap,foto_profil')
            ->forUser($user->user_id)
            ->latest()
            ->limit(8)
            ->get();

        $unreadCount = NotificationService::unreadCount($user->user_id);

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications->map(function (Notification $n) use ($user) {
                return [
                    'id' => $n->notification_id,
                    'judul' => $n->judul,
                    'isi' => $n->pesan,
                    'type' => $n->tipe,
                    'icon' => static::ICON_MAP[$n->tipe] ?? 'notifications',
                    'url' => $n->url,
                    'target' => $n->url ?? $this->notifTarget($user->role?->nama_role, $n->tipe),
                    'waktu' => $n->created_at?->diffForHumans() ?? '-',
                    'status' => $n->is_read ? 1 : 0,
                    'aktor_nama' => $n->aktor?->nama_lengkap,
                    'aktor_foto' => $n->aktor?->foto_profil_url,
                ];
            }),
        ]);
    }

    public function popupAktivitas(Request $request): JsonResponse
    {
        $user = Auth::user();
        $since = $request->input('since');
        $now = now()->toDateTimeString();
        $items = [];

        if ($since) {
            try {
                $since = \Carbon\Carbon::parse($since)->toDateTimeString();
            } catch (\Throwable $e) {
                $since = null;
            }

            // Semua notifikasi baru (dari aktor lain, sistem, maupun aksi sendiri)
            // ikut popup di kanan-atas.
            $notifBaru = Notification::latest()
                ->where('user_id', $user->user_id)
                ->when($since, fn ($q) => $q->where('created_at', '>', $since))
                ->take(5)
                ->get();

            foreach ($notifBaru as $n) {
                $items[] = [
                    'id_notif' => $n->notification_id,
                    'icon' => static::ICON_MAP[$n->tipe] ?? 'notifications',
                    'judul' => $n->judul,
                    'message' => $n->pesan,
                    'type' => $n->tipe,
                    'url' => $n->url,
                    'target' => $n->url ?? $this->notifTarget($user->role?->nama_role, $n->tipe),
                ];
            }

            // Dedup per id_notif
            $seen = [];
            $items = array_values(array_filter($items, function ($item) use (&$seen) {
                $key = isset($item['id_notif']) ? 'notif_'.$item['id_notif'] : ($item['message'] ?? '');
                if (isset($seen[$key])) {
                    return false;
                }
                $seen[$key] = true;

                return true;
            }));
        }

        return response()->json([
            'now' => $now,
            'items' => $items,
        ]);
    }

    public function markRead(Request $request, Notification $notification): JsonResponse
    {
        abort_if($notification->user_id !== Auth::id(), 403);

        if ($notification->dibaca_pada === null) {
            $notification->update(['dibaca_pada' => now()]);
        }

        return response()->json([
            'success' => true,
            'target' => $notification->url ?? $this->notifTarget(Auth::user()->role?->nama_role, $notification->tipe),
        ]);
    }

    public function markAllRead(): JsonResponse
    {
        NotificationService::markAllRead(Auth::id());

        return response()->json(['success' => true, 'message' => 'Semua notifikasi telah dibaca']);
    }

    /**
     * Peta tipe notifikasi -> halaman relevan per role (mirror beautycare).
     */
    protected function notifTarget(?string $role, string $tipe): ?string
    {
        $role = trim($role ?? '');

        $map = [
            Notification::TIPE_ORDER => [
                'Super Admin' => 'superadmin.data-pesanan',
                'Admin' => 'admin.pesanan',
                'Owner' => 'owner.pesanan',
                'Gudang' => 'gudang.stok',
                'Produksi' => 'produksi.dashboard',
                'Customer' => 'customer.checkout',
            ],
            Notification::TIPE_PEMBAYARAN => [
                'Super Admin' => 'superadmin.data-pembayaran',
                'Admin' => 'admin.verifikasi-pembayaran',
                'Owner' => 'owner.keuangan',
                'Gudang' => 'gudang.barang-keluar',
                'Produksi' => 'produksi.dashboard',
                'Customer' => 'customer.checkout',
            ],
            Notification::TIPE_PENGIRIMAN => [
                'Super Admin' => 'superadmin.data-pesanan',
                'Admin' => 'admin.pengiriman',
                'Owner' => 'owner.pesanan',
                'Gudang' => 'gudang.barang-keluar',
                'Produksi' => 'produksi.dashboard',
                'Customer' => 'customer.order-tracking',
            ],
            Notification::TIPE_KOMPLAIN => [
                'Super Admin' => 'superadmin.komplain',
                'Admin' => 'admin.komplain',
                'Owner' => 'owner.ulasan',
                'Gudang' => 'gudang.pemeriksaan',
                'Produksi' => 'produksi.pemeriksaan-kualitas',
                'Customer' => 'customer.order-tracking',
            ],
            Notification::TIPE_WALLET => [
                'Super Admin' => 'superadmin.permintaan-penarikan',
                'Admin' => 'admin.dashboard',
                'Owner' => 'owner.pencairan-dana',
                'Gudang' => 'gudang.dashboard',
                'Produksi' => 'produksi.dashboard',
                'Customer' => 'customer.account',
            ],
            Notification::TIPE_PROMO => [
                'Super Admin' => 'superadmin.moderasi-produk',
                'Admin' => 'admin.promo',
                'Owner' => 'owner.promo',
                'Gudang' => 'gudang.dashboard',
                'Produksi' => 'produksi.dashboard',
                'Customer' => 'customer.home',
            ],
            Notification::TIPE_SISTEM => [
                'Super Admin' => 'superadmin.dashboard',
                'Admin' => 'admin.dashboard',
                'Owner' => 'owner.dashboard',
                'Gudang' => 'gudang.dashboard',
                'Produksi' => 'produksi.dashboard',
                'Customer' => 'customer.account',
            ],
        ];

        $routeName = $map[$tipe][$role] ?? null;

        if ($routeName && \Illuminate\Support\Facades\Route::has($routeName)) {
            return route($routeName);
        }

        return null;
    }
}