<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Role;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Services\NotificationService;
use App\Support\OwnerContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $warehouses = Warehouse::with('staff')
            ->where('store_id', $storeId)
            ->get();

        $summary = [
            'total' => $warehouses->count(),
            'unit' => 0,
            'menipis' => 0,
            'kapasitas' => $warehouses->avg('kapasitas') ?? 0,
        ];

        $menungguPersetujuan = $storeId
            ? StockTransfer::with(['fromWarehouse', 'toWarehouse', 'requester', 'items.productVariant.product'])
                ->where('status', StockTransfer::STATUS_REQUESTED)
                ->whereHas('fromWarehouse', fn ($query) => $query->where('store_id', $storeId))
                ->orderByDesc('created_at')
                ->orderByDesc('stock_transfer_id')
                ->limit(10)
                ->get()
            : collect();

        $calonPetugas = \App\Models\User::whereHas('role', fn ($q) => $q->where('nama_role', Role::GUDANG))
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get(['user_id', 'nama_lengkap', 'email']);

        return view('Owner.gudang.index', compact('warehouses', 'summary', 'menungguPersetujuan', 'calonPetugas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $storeId = OwnerContext::firstStoreId();
        if (! $storeId) {
            return back()->with('error', __('Belum ada toko untuk ditambah gudang.'));
        }

        $data = $request->validate([
            'nama_gudang' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
        ], [
            'nama_gudang.required' => __('Nama gudang wajib diisi.'),
        ]);

        Warehouse::create([
            'store_id' => $storeId,
            'nama_gudang' => trim($data['nama_gudang']),
            'alamat' => $data['alamat'] ?? null,
            'nomor_telepon' => $data['nomor_telepon'] ?? null,
            'status' => Warehouse::STATUS_AKTIF,
        ]);

        return back()->with('success', sprintf(__('Gudang "%s" ditambahkan.'), $data['nama_gudang']));
    }

    public function update(Request $request, Warehouse $warehouse): RedirectResponse
    {
        if (! OwnerContext::canAccessStore($warehouse->store_id)) {
            return back()->with('error', __('Gudang ini di luar toko Anda.'));
        }

        $data = $request->validate([
            'nama_gudang' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
        ], [
            'nama_gudang.required' => __('Nama gudang wajib diisi.'),
        ]);

        $warehouse->update([
            'nama_gudang' => trim($data['nama_gudang']),
            'alamat' => $data['alamat'] ?? null,
            'nomor_telepon' => $data['nomor_telepon'] ?? null,
        ]);

        return back()->with('success', __('Data gudang diperbarui.'));
    }

    public function toggle(Request $request, Warehouse $warehouse): RedirectResponse
    {
        if (! OwnerContext::canAccessStore($warehouse->store_id)) {
            return back()->with('error', __('Gudang ini di luar toko Anda.'));
        }

        if ($warehouse->status === Warehouse::STATUS_AKTIF) {
            $aktifLain = Warehouse::where('store_id', $warehouse->store_id)
                ->where('status', Warehouse::STATUS_AKTIF)
                ->where('warehouse_id', '!=', $warehouse->warehouse_id)
                ->exists();
            if (! $aktifLain) {
                return back()->with('error', __('Tidak bisa menonaktifkan satu-satunya gudang aktif.'));
            }
            $stok = (int) \App\Models\WarehouseStock::where('warehouse_id', $warehouse->warehouse_id)->sum('jumlah_stok');
            if ($stok > 0) {
                return back()->with('error', sprintf(__('Gudang masih menyimpan %d pcs stok. Pindahkan dulu sebelum dinonaktifkan.'), $stok));
            }
            $warehouse->update(['status' => Warehouse::STATUS_NONAKTIF]);

            return back()->with('success', __('Gudang dinonaktifkan.'));
        }

        $warehouse->update(['status' => Warehouse::STATUS_AKTIF]);

        return back()->with('success', __('Gudang diaktifkan kembali.'));
    }

    public function assignStaff(Request $request, Warehouse $warehouse): RedirectResponse
    {
        if (! OwnerContext::canAccessStore($warehouse->store_id)) {
            return back()->with('error', __('Gudang ini di luar toko Anda.'));
        }

        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,user_id'],
        ], [
            'user_id.required' => __('Pilih staff gudang.'),
        ]);

        $user = \App\Models\User::where('user_id', $data['user_id'])
            ->whereHas('role', fn ($q) => $q->where('nama_role', Role::GUDANG))
            ->where('status', 'aktif')
            ->first();
        if (! $user) {
            return back()->with('error', __('User harus staff Gudang yang aktif.'));
        }

        \App\Models\WarehouseStaff::updateOrCreate(
            ['warehouse_id' => $warehouse->warehouse_id, 'user_id' => $user->user_id],
            ['tanggal_penugasan' => now(), 'status' => 'aktif']
        );

        return back()->with('success', sprintf(__('%s ditugaskan ke %s.'), $user->nama_lengkap, $warehouse->nama_gudang));
    }

    public function setujui(Request $request, StockTransfer $stockTransfer): RedirectResponse
    {
        try {
            DB::transaction(function () use ($stockTransfer) {
                $transfer = StockTransfer::with('items')
                    ->where('stock_transfer_id', $stockTransfer->stock_transfer_id)
                    ->lockForUpdate()
                    ->first();

                if (! $transfer) {
                    throw new \RuntimeException(__('Pemindahan tidak ditemukan.'));
                }

                $storeId = $transfer->fromWarehouse?->store_id;
                if (! $storeId || ! OwnerContext::canAccessStore($storeId)) {
                    throw new \RuntimeException(__('Anda tidak berhak menyetujui pemindahan ini.'));
                }

                if (! $transfer->canTransitionTo(StockTransfer::STATUS_APPROVED)) {
                    throw new \RuntimeException(__('Pemindahan sudah tidak dapat disetujui.'));
                }

                foreach ($transfer->items as $item) {
                    $asal = WarehouseStock::where('warehouse_id', $transfer->from_warehouse_id)
                        ->where('product_variant_id', $item->product_variant_id)
                        ->lockForUpdate()
                        ->first();

                    if (! $asal || $asal->jumlah_stok < $item->jumlah) {
                        throw new \RuntimeException(__('Stok di gudang asal tidak mencukupi untuk pemindahan ini.'));
                    }

                    $affected = WarehouseStock::where('warehouse_stock_id', $asal->warehouse_stock_id)
                        ->where('jumlah_stok', '>=', $item->jumlah)
                        ->decrement('jumlah_stok', $item->jumlah);

                    if ($affected === 0) {
                        throw new \RuntimeException(__('Stok di gudang asal tidak mencukupi untuk pemindahan ini.'));
                    }

                    StockMovement::create([
                        'warehouse_id' => $transfer->from_warehouse_id,
                        'product_variant_id' => $item->product_variant_id,
                        'tipe_pergerakan' => StockMovement::TIPE_MUTASI_KELUAR,
                        'jumlah' => $item->jumlah,
                        'sumber_tipe' => StockMovement::SUMBER_STOCK_TRANSFER,
                        'sumber_id' => $transfer->stock_transfer_id,
                        'alasan' => 'Pemindahan stok keluar',
                        'dibuat_oleh' => Auth::id(),
                    ]);
                }

                $affected = StockTransfer::where('stock_transfer_id', $transfer->stock_transfer_id)
                    ->update([
                        'status' => StockTransfer::STATUS_APPROVED,
                        'approved_by' => Auth::id(),
                    ]);

                if ($affected === 0) {
                    throw new \RuntimeException(__('Gagal menyetujui pemindahan.'));
                }
            }, 5);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error',__('Gagal menyetujui pemindahan.'));
        }

        $this->notifyPersetujuan($stockTransfer, 'disetujui', sprintf(__('Pemindahan #TRF-%d disetujui. Stok gudang asal telah dikurangi.'), $stockTransfer->stock_transfer_id));

        return back()->with('success',__('Pemindahan disetujui. Stok gudang asal telah dikurangi.'));
    }

    public function tolak(Request $request, StockTransfer $stockTransfer): RedirectResponse
    {
        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:500',
        ], [
            'alasan.required' => __('Alasan penolakan wajib diisi.'),
            'alasan.min' => __('Alasan penolakan minimal 10 karakter.'),
        ]);

        try {
            DB::transaction(function () use ($stockTransfer, $data) {
                $transfer = StockTransfer::where('stock_transfer_id', $stockTransfer->stock_transfer_id)
                    ->lockForUpdate()
                    ->first();

                if (! $transfer) {
                    throw new \RuntimeException(__('Pemindahan tidak ditemukan.'));
                }

                $storeId = $transfer->fromWarehouse?->store_id;
                if (! $storeId || ! OwnerContext::canAccessStore($storeId)) {
                    throw new \RuntimeException(__('Anda tidak berhak menolak pemindahan ini.'));
                }

                if (! $transfer->canTransitionTo(StockTransfer::STATUS_CANCELLED)) {
                    throw new \RuntimeException(__('Pemindahan sudah tidak dapat dibatalkan.'));
                }

                $affected = StockTransfer::where('stock_transfer_id', $transfer->stock_transfer_id)
                    ->update([
                        'status' => StockTransfer::STATUS_CANCELLED,
                        'alasan_penolakan' => $data['alasan'],
                        'dibatalkan_pada' => now(),
                    ]);

                if ($affected === 0) {
                    throw new \RuntimeException(__('Gagal menolak pemindahan.'));
                }
            }, 5);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()->with('error',__('Gagal menolak pemindahan.'));
        }

        $this->notifyPersetujuan($stockTransfer, 'ditolak', sprintf(__('Pemindahan #TRF-%d ditolak. Alasan: %s'), $stockTransfer->stock_transfer_id, $data['alasan']));

        return back()->with('success',__('Pemindahan ditolak.'));
    }

    private function notifyPersetujuan(StockTransfer $transfer, string $aksi, string $pesan): void
    {
        if ($transfer->requested_by) {
            Notification::create([
                'user_id' => $transfer->requested_by,
                'tipe' => Notification::TIPE_SISTEM,
                'judul' => sprintf(__('Pemindahan Stok %s'), $aksi === 'disetujui' ? __('Disetujui') : __('Ditolak')),
                'pesan' => $pesan,
                'url' => route('gudang.pemindahan'),
            ]);
        }

        NotificationService::sendToRole(
            Role::GUDANG,
            Notification::TIPE_SISTEM,
            sprintf(__('Pemindahan Stok %s'), $aksi === 'disetujui' ? __('Disetujui') : __('Ditolak')),
            $pesan,
            Auth::id(),
            route('gudang.pemindahan')
        );
    }
}
