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
    public function index()
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        $orders = Order::whereIn('store_id', $storeIds)
            ->where('status', Order::STATUS_MENUNGGU_QC)
            ->with(['items.productVariant.product', 'bahanList', 'checkout', 'store'])
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'menunggu_qc' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_MENUNGGU_QC)->count(),
            'siap_kirim' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_SIAP_KIRIM)->count(),
        ];

        return view('Produksi.pemeriksaan-kualitas.index', compact('orders', 'stats'));
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
            'jumlah_gagal' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:500',
        ], [
            'jumlah_lulus.required' => 'Jumlah lulus QC wajib diisi.',
            'jumlah_lulus.min' => 'Jumlah lulus minimal 0.',
        ]);

        $qcStatus = ($data['jumlah_gagal'] ?? 0) > 0 ? QualityCheck::STATUS_SEBAGIAN : QualityCheck::STATUS_LULUS;

        QualityCheck::create([
            'order_id' => $order->order_id,
            'checked_by' => ActivityLogger::resolveActorId(),
            'jumlah_lulus' => $data['jumlah_lulus'],
            'jumlah_gagal' => $data['jumlah_gagal'] ?? 0,
            'status' => $qcStatus,
            'catatan' => $data['catatan'] ?? null,
            'diperiksa_pada' => now(),
        ]);

        $lama = $order->only(['status']);
        $order->update([
            'status' => Order::STATUS_SIAP_KIRIM,
            'tanggal_qc' => now(),
            'tanggal_packing' => now(),
        ]);

        ActivityLogger::log('produksi.qc.complete', Order::class, $order->order_id, $lama,
            ['status' => Order::STATUS_SIAP_KIRIM, 'lulus' => $data['jumlah_lulus'], 'gagal' => $data['jumlah_gagal'] ?? 0],
            sprintf('QC + Packing selesai untuk pesanan %s. Lulus: %d, Gagal: %d.', $order->nomor_order, $data['jumlah_lulus'], $data['jumlah_gagal'] ?? 0));

        NotificationService::sendToRole(Role::ADMIN, Notification::TIPE_SISTEM,
            'Produk Siap Dikirim',
            sprintf('Pesanan %s telah lulus QC + packing. Siap dikirim ke customer.', $order->nomor_order),
            ActivityLogger::resolveActorId(),
            route('admin.pengiriman'));

        return back()->with('toast', [
            'message' => "Pesanan {$order->nomor_order} lulus QC + packing. Siap dikirim.",
            'icon' => 'task_alt',
        ]);
    }
}
