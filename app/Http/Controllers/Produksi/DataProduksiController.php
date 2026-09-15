<?php

namespace App\Http\Controllers\Produksi;

use App\Http\Controllers\Controller;
use App\Models\BahanProduksi;
use App\Models\Notification;
use App\Models\Order;
use App\Models\ProductionOrderBahan;
use App\Models\Role;
use App\Models\StoreStaff;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataProduksiController extends Controller
{
    public function index()
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())
            ->where('status', 'aktif')
            ->pluck('store_id')
            ->all();

        $orders = Order::whereIn('store_id', $storeIds)
            ->whereIn('status', [Order::STATUS_MENUNGGU_PRODUKSI, Order::STATUS_DIPROSES, Order::STATUS_MENUNGGU_QC])
            ->with(['items.productVariant.product', 'bahanList.bahan', 'bahanList.creator', 'checkout', 'store'])
            ->orderByRaw("CASE WHEN status = 'menunggu_produksi' THEN 0 WHEN status = 'diproses' THEN 1 WHEN status = 'menunggu_qc' THEN 2 ELSE 3 END")
            ->orderByDesc('created_at')
            ->paginate(15);

        $bahanList = BahanProduksi::whereIn('store_id', $storeIds)
            ->where('status', BahanProduksi::STATUS_AKTIF)
            ->orderBy('nama_bahan')
            ->get();

        $stats = [
            'menunggu' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_MENUNGGU_PRODUKSI)->count(),
            'diproses' => Order::whereIn('store_id', $storeIds)->where('status', Order::STATUS_DIPROSES)->count(),
            'terlambat' => Order::whereIn('store_id', $storeIds)
                ->where('status', Order::STATUS_DIPROSES)
                ->whereNotNull('tgl_berakhir_produksi')
                ->where('tgl_berakhir_produksi', '<', now())
                ->count(),
        ];

        return view('Produksi.data-produksi.index', compact('orders', 'bahanList', 'stats'));
    }

    public function accept(Request $request, Order $order)
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())->where('status', 'aktif')->pluck('store_id')->all();
        if (! in_array($order->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Pesanan di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        if ($order->status !== Order::STATUS_DIPROSES) {
            return back()->with('toast', ['message' => 'Hanya pesanan yang sudah diproses yang bisa di-accept.', 'icon' => 'gpp_maybe']);
        }

        if ($order->produksi_dimulai_pada) {
            return back()->with('toast', ['message' => 'Produksi sudah dimulai sebelumnya.', 'icon' => 'gpp_maybe']);
        }

        $lama = $order->only(['status', 'produksi_dimulai_pada']);
        $order->update(['produksi_dimulai_pada' => now()]);

        ActivityLogger::log('produksi.order.accept', Order::class, $order->order_id, $lama,
            ['produksi_dimulai_pada' => now()],
            sprintf('Produksi menerima pesanan %s.', $order->nomor_order));

        NotificationService::sendToRole(Role::ADMIN, Notification::TIPE_SISTEM,
            'Produksi Diterima',
            sprintf('Produksi telah menerima pesanan %s dan mulai bekerja.', $order->nomor_order),
            ActivityLogger::resolveActorId(),
            route('admin.pesanan'));

        return back()->with('toast', ['message' => "Pesanan {$order->nomor_order} diterima, produksi dimulai.", 'icon' => 'task_alt']);
    }

    public function reject(Request $request, Order $order)
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())->where('status', 'aktif')->pluck('store_id')->all();
        if (! in_array($order->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Pesanan di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        if ($order->status !== Order::STATUS_DIPROSES) {
            return back()->with('toast', ['message' => 'Hanya pesanan yang sudah diproses yang bisa ditolak.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'catatan' => 'required|string|min:10|max:500',
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
            'catatan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $lama = $order->only(['status', 'produksi_catatan_tolak']);
        $order->update([
            'status' => Order::STATUS_MENUNGGU_PRODUKSI,
            'produksi_catatan_tolak' => $data['catatan'],
            'produksi_dimulai_pada' => null,
        ]);

        ActivityLogger::log('produksi.order.reject', Order::class, $order->order_id, $lama,
            ['status' => Order::STATUS_MENUNGGU_PRODUKSI, 'produksi_catatan_tolak' => $data['catatan']],
            sprintf('Produksi menolak pesanan %s. Alasan: %s', $order->nomor_order, $data['catatan']));

        NotificationService::sendToRole(Role::ADMIN, Notification::TIPE_SISTEM,
            'Produksi Ditolak',
            sprintf('Produksi menolak pesanan %s. Alasan: %s', $order->nomor_order, $data['catatan']),
            ActivityLogger::resolveActorId(),
            route('admin.pesanan'));

        return back()->with('toast', ['message' => "Pesanan {$order->nomor_order} ditolak, dikembalikan ke Admin.", 'icon' => 'block']);
    }

    public function storeBahan(Request $request, Order $order)
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())->where('status', 'aktif')->pluck('store_id')->all();
        if (! in_array($order->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Pesanan di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        if ($order->status !== Order::STATUS_DIPROSES) {
            return back()->with('toast', ['message' => 'Hanya pesanan yang diproses yang bisa ditambah bahan.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'bahan' => ['required', 'array', 'min:1'],
            'bahan.*.bahan_id' => ['nullable', 'exists:bahan_produksi,bahan_id'],
            'bahan.*.nama_bahan' => ['required', 'string', 'max:150'],
            'bahan.*.jumlah' => ['required', 'numeric', 'min:0.01'],
            'bahan.*.satuan' => ['required', 'string', 'max:20'],
            'bahan.*.catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'bahan.required' => 'Input minimal 1 bahan.',
            'bahan.min' => 'Input minimal 1 bahan.',
            'bahan.*.nama_bahan.required' => 'Nama bahan wajib diisi.',
            'bahan.*.jumlah.required' => 'Jumlah wajib diisi.',
            'bahan.*.satuan.required' => 'Satuan wajib diisi.',
        ]);

        DB::transaction(function () use ($order, $data) {
            foreach ($data['bahan'] as $bahan) {
                ProductionOrderBahan::create([
                    'order_id' => $order->order_id,
                    'bahan_id' => $bahan['bahan_id'] ?? null,
                    'nama_bahan' => $bahan['nama_bahan'],
                    'jumlah' => $bahan['jumlah'],
                    'satuan' => $bahan['satuan'],
                    'catatan' => $bahan['catatan'] ?? null,
                    'created_by' => ActivityLogger::resolveActorId(),
                ]);
            }
        });

        ActivityLogger::log('produksi.bahan.add', Order::class, $order->order_id, null,
            ['bahan_count' => count($data['bahan'])],
            sprintf('Produksi menambah %d bahan untuk pesanan %s.', count($data['bahan']), $order->nomor_order));

        return back()->with('toast', ['message' => 'Bahan tambahan berhasil disimpan.', 'icon' => 'task_alt']);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $storeIds = StoreStaff::where('user_id', auth()->id())->where('status', 'aktif')->pluck('store_id')->all();
        if (! in_array($order->store_id, $storeIds, true)) {
            return back()->with('toast', ['message' => 'Pesanan di luar scope toko Anda.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'jumlah_berhasil' => 'required|integer|min:0',
            'jumlah_gagal' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:500',
        ], [
            'jumlah_berhasil.required' => 'Jumlah berhasil wajib diisi.',
            'jumlah_berhasil.min' => 'Jumlah berhasil minimal 0.',
        ]);

        if ($order->status !== Order::STATUS_DIPROSES) {
            return back()->with('toast', ['message' => 'Hanya pesanan yang sedang diproses yang bisa diselesaikan.', 'icon' => 'gpp_maybe']);
        }

        if (! $order->produksi_dimulai_pada) {
            return back()->with('toast', ['message' => 'Anda belum accept produksi ini.', 'icon' => 'gpp_maybe']);
        }

        $lama = $order->only(['status']);
        $order->update([
            'status' => Order::STATUS_MENUNGGU_QC,
            'jumlah_berhasil' => $data['jumlah_berhasil'],
            'jumlah_gagal' => $data['jumlah_gagal'] ?? 0,
        ]);

        ActivityLogger::log('produksi.order.complete', Order::class, $order->order_id, $lama,
            ['status' => Order::STATUS_MENUNGGU_QC, 'jumlah_berhasil' => $data['jumlah_berhasil'], 'jumlah_gagal' => $data['jumlah_gagal'] ?? 0],
            sprintf('Produksi selesai untuk pesanan %s. Berhasil: %d, Gagal: %d.', $order->nomor_order, $data['jumlah_berhasil'], $data['jumlah_gagal'] ?? 0));

        NotificationService::sendToRole(Role::ADMIN, Notification::TIPE_SISTEM,
            'Produksi Selesai — Menunggu QC',
            sprintf('Pesanan %s telah selesai diproduksi. Menunggu QC + Packing.', $order->nomor_order),
            ActivityLogger::resolveActorId(),
            route('admin.pesanan'));

        return back()->with('toast', ['message' => "Pesanan {$order->nomor_order} selesai diproduksi. Menunggu QC.", 'icon' => 'task_alt']);
    }
}
