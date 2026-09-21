<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Role;
use App\Models\StoreExpense;
use App\Models\WalletTransaction;
use App\Services\NotificationService;
use App\Support\ActivityLogger;
use App\Support\AdminContext;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();

        $pemasukan = WalletTransaction::whereHas('wallet', fn ($q) => $q->whereIn('store_id', $storeIds))
            ->whereIn('jenis_transaksi', [
                WalletTransaction::JENIS_PENJUALAN_MASUK,
                WalletTransaction::JENIS_KOMISI_MASUK,
                WalletTransaction::JENIS_PEMASUKAN,
            ])
            ->with('wallet.store')
            ->orderByDesc('created_at')
            ->paginate(10, ['*'], 'pemasukan_page');

        $pengeluaran = StoreExpense::whereIn('store_id', $storeIds)
            ->orderByDesc('tanggal')
            ->paginate(10, ['*'], 'pengeluaran_page');

        $stats = [
            'total_pemasukan' => WalletTransaction::whereHas('wallet', fn ($q) => $q->whereIn('store_id', $storeIds))
                ->whereIn('jenis_transaksi', [
                    WalletTransaction::JENIS_PENJUALAN_MASUK,
                    WalletTransaction::JENIS_KOMISI_MASUK,
                    WalletTransaction::JENIS_PEMASUKAN,
                ])->sum('jumlah'),
            'total_pengeluaran' => StoreExpense::whereIn('store_id', $storeIds)->sum('nominal'),
        ];
        $stats['total_bersih'] = $stats['total_pemasukan'] - $stats['total_pengeluaran'];

        return view('Admin.transaksi.index', compact('pemasukan', 'pengeluaran', 'stats'));
    }

    public function storePemasukan(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $storeId = $storeIds[0] ?? null;
        if (! $storeId) {
            return back()->with('toast', ['message' => 'Admin belum ditugaskan ke toko mana pun.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'required|string|max:500',
        ]);

        $wallet = \App\Models\Wallet::firstOrCreate(
            ['store_id' => $storeId],
            ['saldo_tersedia' => 0, 'saldo_tertahan' => 0]
        );

        $saldoSebelum = $wallet->saldo_tersedia;
        $wallet->increment('saldo_tersedia', $data['jumlah']);

        WalletTransaction::create([
            'wallet_id' => $wallet->wallet_id,
            'jenis_transaksi' => WalletTransaction::JENIS_PEMASUKAN,
            'jumlah' => $data['jumlah'],
            'saldo_sebelum' => $saldoSebelum,
            'saldo_sesudah' => $saldoSebelum + $data['jumlah'],
            'keterangan' => $data['keterangan'],
        ]);

        Notification::fireSelf(Notification::TIPE_WALLET, 'Pemasukan Dicatat',
            sprintf('Pemasukan Rp %s dicatat.', number_format($data['jumlah'], 0, ',', '.')),
            route('admin.transaksi'));

        return back()->with('toast', ['message' => 'Pemasukan berhasil dicatat.', 'icon' => 'task_alt']);
    }

    public function storePengeluaran(Request $request)
    {
        $storeIds = AdminContext::assignedStoreIds();
        $storeId = $storeIds[0] ?? null;
        if (! $storeId) {
            return back()->with('toast', ['message' => 'Admin belum ditugaskan ke toko mana pun.', 'icon' => 'gpp_maybe']);
        }

        $data = $request->validate([
            'nama' => 'required|string|max:150',
            'kategori' => 'nullable|string|max:100',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        $data['store_id'] = $storeId;
        $data['kategori'] = $data['kategori'] ?? 'Lainnya';
        $data['dibuat_oleh'] = ActivityLogger::resolveActorId();

        StoreExpense::create($data);

        Notification::fireSelf(Notification::TIPE_WALLET, 'Pengeluaran Dicatat',
            sprintf('Pengeluaran Rp %s dicatat.', number_format($data['nominal'], 0, ',', '.')),
            route('admin.transaksi'));

        return back()->with('toast', ['message' => 'Pengeluaran berhasil dicatat.', 'icon' => 'task_alt']);
    }
}
