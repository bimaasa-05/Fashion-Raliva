<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Order;
use App\Models\QualityCheck;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class PemeriksaanKualitasController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab') === 'siap' ? 'siap' : 'menunggu';

        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        $orders = Order::whereIn('store_id', $storeIds)
            ->where('status', $tab === 'siap' ? Order::STATUS_SIAP_KIRIM : Order::STATUS_MENUNGGU_QC)
            ->with(['items.productVariant.product', 'bahanList', 'checkout', 'store', 'qualityChecks', 'shipments'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'menunggu_qc' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_MENUNGGU_QC)->count(),
            'siap_kirim' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_SIAP_KIRIM)->count(),
        ];

        return view('Produksi.pemeriksaan-kualitas.index', compact('orders', 'stats', 'tab'));
    }

    public function store(Request $request, Order $order)
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())->where('status', 'aktif')->pluck('store_id')->all();
        if (! in_array($order->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Pesanan di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        if ($order->status !== Order::STATUS_MENUNGGU_QC) {
            return back()->with('toast', ['message' => 'Hanya pesanan menunggu QC yang bisa diproses.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'jumlah_lulus' => 'required|integer|min:0',
            'catatan' => 'nullable|string|max:500',
        ], [
            'jumlah_lulus.required' => 'Jumlah lulus QC wajib diisi.',
            'jumlah_lulus.min' => 'Jumlah lulus minimal 0.',
        ]);

        $totalQty = (int) $order->items()->sum('quantity');
        if ($data['jumlah_lulus'] > $totalQty) {
            return back()->with('toast', ['message' => "Jumlah lulus melebihi total pesanan ({$totalQty} pcs).", 'icon' => 'gpp_maybe']);
        }

        // Gagal dihitung otomatis; kekurangan (lulus < total) diambil dari Gudang.
        $gagal = max(0, $totalQty - (int) $data['jumlah_lulus']);
        $kurang = max(0, $totalQty - (int) $data['jumlah_lulus']);

        $qcStatus = $gagal > 0 ? QualityCheck::STATUS_SEBAGIAN : QualityCheck::STATUS_LULUS;

        QualityCheck::create([
            'order_id' => $order->order_id,
            'checked_by' => ActivityLogger::resolveActorId(),
            'jumlah_lulus' => $data['jumlah_lulus'],
            'jumlah_gagal' => $gagal,
            'status' => $qcStatus,
            'catatan' => $data['catatan'] ?? null,
            'diperiksa_pada' => now(),
        ]);

        $lama = $order->only(['status']);
        $order->update([
            'status' => Order::STATUS_SIAP_KIRIM,
            'jumlah_berhasil' => $data['jumlah_lulus'],
            'jumlah_gagal' => $gagal,
            'kekurangan_gudang' => $kurang,
            'tanggal_qc' => now(),
            'tanggal_packing' => now(),
        ]);

        ActivityLogger::log('produksi.qc.complete', Order::class, $order->order_id, $lama,
            ['status' => Order::STATUS_SIAP_KIRIM, 'lulus' => $data['jumlah_lulus'], 'gagal' => $gagal, 'kekurangan_gudang' => $kurang],
            sprintf('QC + Packing selesai untuk pesanan %s. Lulus: %d, Gagal (otomatis): %d, Kurang dari Gudang: %d.', $order->nomor_order, $data['jumlah_lulus'], $gagal, $kurang));

        if ($kurang > 0) {
            NotificationService::sendToRoleInStores(Role::GUDANG, [$order->store_id], Notification::TIPE_SISTEM,
                'Kekurangan Produksi — Siapkan dari Gudang',
                sprintf('Pesanan %s kurang %d pcs dari produksi. Siapkan dari stok gudang.', $order->nomor_order, $kurang),
                ActivityLogger::resolveActorId(),
                route('gudang.kekurangan'));
            NotificationService::sendToRoleInStores(Role::ADMIN, [$order->store_id], Notification::TIPE_SISTEM,
                'Kekurangan Produksi Diambil dari Gudang',
                sprintf('Pesanan %s kurang %d pcs; diambil dari stok gudang.', $order->nomor_order, $kurang),
                ActivityLogger::resolveActorId(),
                route('admin.pesanan'));
        }

        NotificationService::sendToRole(Role::ADMIN, Notification::TIPE_SISTEM,
            'Produk Siap Dikirim',
            sprintf('Pesanan %s telah lulus QC + packing. Siap dikirim ke customer.', $order->nomor_order),
            ActivityLogger::resolveActorId(),
            route('admin.pengiriman'));

        return back()->with('toast', [
            'message' => $kurang > 0
                ? "Pesanan {$order->nomor_order} lulus QC. Kurang {$kurang} pcs diambil dari Gudang."
                : "Pesanan {$order->nomor_order} lulus QC + packing. Siap dikirim.",
            'icon' => 'task_alt',
        ]);
    }

    public function tandaiGagal(Request $request, Order $order)
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())->where('status', 'aktif')->pluck('store_id')->all();
        if (! in_array($order->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Pesanan di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        if ($order->status !== Order::STATUS_MENUNGGU_QC) {
            return back()->with('toast', ['message' => 'Hanya pesanan menunggu QC yang bisa ditandai gagal.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'catatan' => 'required|string|min:10|max:500',
        ], [
            'catatan.required' => 'Keterangan gagal wajib diisi (untuk Admin).',
            'catatan.min' => 'Keterangan gagal minimal 10 karakter.',
        ]);

        $lama = $order->only(['status', 'qc_perlu_admin_pada']);
        $order->update([
            'qc_perlu_admin_pada' => now(),
            'qc_perlu_admin_catatan' => $data['catatan'],
        ]);

        ActivityLogger::log('produksi.qc.gagal', Order::class, $order->order_id, $lama,
            ['qc_perlu_admin_pada' => now()->format('Y-m-d H:i:s'), 'qc_perlu_admin_catatan' => $data['catatan']],
            sprintf('QC gagal untuk pesanan %s, menunggu Admin. Keterangan: %s', $order->nomor_order, $data['catatan']));

        NotificationService::sendToRoleInStores(Role::ADMIN, [$order->store_id], Notification::TIPE_SISTEM,
            'QC Gagal — Perlu Tindak Lanjut',
            sprintf('QC pesanan %s gagal dan perlu dihubungi. Keterangan: %s', $order->nomor_order, $data['catatan']),
            ActivityLogger::resolveActorId(),
            route('admin.pesanan', ['status' => Order::STATUS_MENUNGGU_QC]));

        return back()->with('toast', [
            'message' => "Pesanan {$order->nomor_order} ditandai gagal. Admin toko telah dihubungi.",
            'icon' => 'support_agent',
        ]);
    }
}
