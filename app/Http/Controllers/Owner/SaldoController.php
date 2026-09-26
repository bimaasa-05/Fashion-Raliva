<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Refund;
use App\Models\Role;
use App\Models\StoreExpense;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SaldoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $store = $user->ownedStores()->first();

        $period = (int) $request->input('period', 30);
        if (! in_array($period, [7, 30, 90, 365])) {
            $period = 30;
        }
        if (! $store) {
            $wallet = new Wallet(['saldo_tersedia' => 0, 'saldo_tertahan' => 0]);

            return view('Owner.keuangan.index', [
                'wallet' => $wallet,
                'mutations' => collect(),
                'withdrawals' => collect(),
                'refunds' => collect(),
                'summary' => ['pemasukan' => 0, 'pengeluaran' => 0, 'bersih' => 0],
                'chart' => collect(),
                'store' => null,
                'bankAccounts' => collect(),
                'expenses' => collect(),
                'margin' => ['revenue' => 0, 'gross' => 0, 'ebitda' => 0, 'ebit' => 0, 'ebt' => 0, 'net' => 0],
                'totalDicairkan' => 0,
                'period' => $period,
            ]);
        }

        // Auto-create wallet jika belum ada (agar keuangan selalu tampil, tidak nunggu transaksi)
        $wallet = $store->wallet;
        if (! $wallet) {
            $wallet = Wallet::create(['store_id' => $store->store_id, 'saldo_tersedia' => 0, 'saldo_tertahan' => 0]);
            $store->setRelation('wallet', $wallet);
        }

        $bankAccounts = $store->bankAccounts()->with('bank')->get();

        $totalDicairkan = (float) $wallet->withdrawals()
            ->where('status', Withdrawal::STATUS_DIBAYAR)
            ->sum('jumlah');

        $filterKategori = trim((string) $request->input('kategori', ''));
        $filterJenis = trim((string) $request->input('jenis', ''));

        $mutations = $wallet->transactions()
            ->when($filterKategori !== '', fn ($q) => $q->where('kategori', $filterKategori))
            ->when($filterJenis !== '', fn ($q) => $q->where('jenis_transaksi', $filterJenis))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $kategoriList = $wallet->transactions()->select('kategori')->distinct()->pluck('kategori')->filter()->values()->all();
        foreach (['Penjualan', 'Investor', 'Modal', 'Komisi', 'Lainnya'] as $wajib) {
            if (! in_array($wajib, $kategoriList, true)) $kategoriList[] = $wajib;
        }
        $jenisList = $wallet->transactions()->select('jenis_transaksi')->distinct()->pluck('jenis_transaksi')->filter()->values()->all();

        $withdrawals = $wallet->withdrawals()
            ->with('bankAccount.bank')
            ->orderByDesc('diajukan_pada')
            ->get();

        $refunds = Refund::whereIn('order_id', function ($q) use ($store) {
            $q->select('order_id')->from('orders')->where('store_id', $store->store_id);
        })
            ->orderByDesc('diajukan_pada')
            ->get();

        // Ringkasan per periode (7/30/90/365 hari)
        $start = Carbon::now()->subDays($period - 1)->startOfDay();
        $end = Carbon::now()->endOfDay();
        $monthTx = $wallet->transactions()
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $pemasukan = $monthTx->whereIn('jenis_transaksi', [
            WalletTransaction::JENIS_PENJUALAN_MASUK,
            WalletTransaction::JENIS_KOMISI_MASUK,
            WalletTransaction::JENIS_PEMASUKAN,
        ])->sum('jumlah');

        $pengeluaran = $monthTx->whereNotIn('jenis_transaksi', [
            WalletTransaction::JENIS_PENJUALAN_MASUK,
            WalletTransaction::JENIS_KOMISI_MASUK,
            WalletTransaction::JENIS_PEMASUKAN,
        ])->sum(function ($t) {
            return abs((float) $t->jumlah);
        });

        $summary = [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'bersih' => $pemasukan - $pengeluaran,
        ];

        // Data tren harian: 7 / 30 / 90 hari terakhir (saldo akhir hari).
        $grafik = $request->input('grafik', '30hari');
        if (! in_array($grafik, ['7hari', '30hari', '90hari'], true)) {
            $grafik = '30hari';
        }
        $hariRange = ['7hari' => 7, '30hari' => 30, '90hari' => 90][$grafik];

        $chart = collect(range($hariRange - 1, 0))->map(function ($i) use ($wallet) {
            $hari = Carbon::now()->subDays($i);
            $saldoAkhir = (float) $wallet->transactions()
                ->where('created_at', '<=', $hari->copy()->endOfDay())
                ->orderByDesc('created_at')
                ->value('saldo_sesudah');

            return [
                'label' => $hari->translatedFormat('d M'),
                'saldo' => $saldoAkhir,
            ];
        });

        $filterKatExp = trim((string) $request->input('kat_exp', ''));

        $expenses = StoreExpense::where('store_id', $store->store_id)
            ->when($filterKatExp !== '', fn ($q) => $q->where('kategori', $filterKatExp))
            ->orderByDesc('tanggal')
            ->get();

        $katExpList = StoreExpense::where('store_id', $store->store_id)
            ->select('kategori')->distinct()->pluck('kategori')->filter()->values()->all();

        $filterKatIn = trim((string) $request->input('kat_in', ''));
        $pemasukanList = $wallet->transactions()
            ->whereIn('jenis_transaksi', [
                WalletTransaction::JENIS_PENJUALAN_MASUK,
                WalletTransaction::JENIS_KOMISI_MASUK,
                WalletTransaction::JENIS_PEMASUKAN,
            ])
            ->when($filterKatIn !== '', fn ($q) => $q->where('kategori', $filterKatIn))
            ->orderByDesc('created_at')
            ->get();
        $katInList = $wallet->transactions()
            ->whereIn('jenis_transaksi', [
                WalletTransaction::JENIS_PENJUALAN_MASUK,
                WalletTransaction::JENIS_KOMISI_MASUK,
                WalletTransaction::JENIS_PEMASUKAN,
            ])
            ->select('kategori')->distinct()->pluck('kategori')->filter()->values()->all();
        foreach (['Penjualan', 'Investor', 'Modal', 'Komisi', 'Lainnya'] as $wajib) {
            if (! in_array($wajib, $katInList, true)) $katInList[] = $wajib;
        }

        // Estimasi margin (asumsi HPP 60% revenue, pajak 25% laba, tanpa D&A/bunga).
        $revenue = $pemasukan;
        $hpp = $revenue * 0.60;
        $grossProfit = $revenue - $hpp;
        $operasional = (float) $expenses->where('tanggal', '>=', $start->toDateString())->sum('nominal');
        $ebitda = $grossProfit - $operasional;
        $ebit = $ebitda;
        $ebt = $ebit;
        $netProfit = $ebt * 0.75;

        $margin = [
            'revenue' => $revenue,
            'gross' => $grossProfit,
            'ebitda' => $ebitda,
            'ebit' => $ebit,
            'ebt' => $ebt,
            'net' => $netProfit,
        ];

        return view('Owner.keuangan.index', compact(
            'wallet', 'bankAccounts', 'totalDicairkan',
            'mutations', 'withdrawals', 'refunds', 'summary', 'chart',
            'expenses', 'margin', 'store', 'period',
            'kategoriList', 'jenisList', 'filterKategori', 'filterJenis',
            'katExpList', 'filterKatExp', 'grafik',
            'pemasukanList', 'katInList', 'filterKatIn'
        ));
    }

    public function storePengeluaran(Request $request)
    {
        $user = $request->user();
        $store = $user->ownedStores()->first();
        if (! $store) {
            return back()->with('error', 'Toko tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'string', 'max:100'],
            'nominal' => ['required', 'numeric', 'min:1'],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $wallet = $store->wallet;
        if (! $wallet) {
            $wallet = \App\Models\Wallet::create(['store_id' => $store->store_id, 'saldo_tersedia' => 0, 'saldo_tertahan' => 0]);
        }

        DB::transaction(function () use ($store, $wallet, $validated) {
            StoreExpense::create([
                'store_id' => $store->store_id,
                'nama' => $validated['nama'],
                'kategori' => $validated['kategori'],
                'nominal' => $validated['nominal'],
                'tanggal' => $validated['tanggal'],
            ]);

            $saldoSebelum = (float) $wallet->saldo_tersedia;
            $wallet->decrement('saldo_tersedia', $validated['nominal']);

            WalletTransaction::create([
                'wallet_id' => $wallet->wallet_id,
                'jenis_transaksi' => WalletTransaction::JENIS_PENGELUARAN,
                'kategori' => $validated['kategori'],
                'jumlah' => $validated['nominal'],
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSebelum - (float) $validated['nominal'],
                'keterangan' => 'Pengeluaran: '.$validated['nama'],
            ]);
        });

        Notification::fireSelf(Notification::TIPE_WALLET, 'Pengeluaran Dicatat', sprintf('Pengeluaran "%s" senilai Rp %s dicatat.', $validated['nama'], number_format((float) $validated['nominal'], 0, ',', '.')), route('owner.keuangan'));

        return redirect()->route('owner.keuangan')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function storePemasukan(Request $request)
    {
        $user = $request->user();
        $store = $user->ownedStores()->first();
        if (! $store) {
            return back()->with('error', 'Toko tidak ditemukan.');
        }
        $validated = $request->validate([
            'sumber' => ['required', 'string', 'max:150'],
            'nominal' => ['required', 'numeric', 'min:1'],
            'tanggal' => ['required', 'date', 'before_or_equal:today'],
            'kategori' => ['required', 'string', 'in:Penjualan,Investor,Modal,Komisi,Lainnya'],
        ]);
        $wallet = $store->wallet;
        if (! $wallet) {
            $wallet = Wallet::create(['store_id' => $store->store_id, 'saldo_tersedia' => 0, 'saldo_tertahan' => 0]);
        }

        // Investor/Modal = omzet saja, tidak masuk saldo tarik. Penjualan/Komisi/Lainnya tetap ke saldo.
        $omzetSaja = in_array($validated['kategori'], ['Investor', 'Modal'], true);
        DB::transaction(function () use ($wallet, $validated, $omzetSaja) {
            $saldoSekarang = (float) $wallet->saldo_tersedia;
            if (! $omzetSaja) {
                $wallet->increment('saldo_tersedia', $validated['nominal']);
            }
            WalletTransaction::create([
                'wallet_id' => $wallet->wallet_id,
                'jenis_transaksi' => \App\Models\WalletTransaction::JENIS_PEMASUKAN,
                'kategori' => $validated['kategori'],
                'jumlah' => $validated['nominal'],
                'saldo_sebelum' => $saldoSekarang,
                'saldo_sesudah' => $saldoSekarang + ($omzetSaja ? 0 : (float) $validated['nominal']),
                'keterangan' => 'Pemasukan: '.$validated['sumber'],
            ]);
        });
        Notification::fireSelf(Notification::TIPE_WALLET, 'Pemasukan Dicatat', sprintf('Pemasukan dari %s senilai Rp %s dicatat.', $validated['sumber'], number_format((float) $validated['nominal'], 0, ',', '.')), route('owner.keuangan'));

        return redirect()->route('owner.keuangan')->with('success', $omzetSaja
            ? 'Pemasukan omzet dicatat (tidak masuk saldo tarik).'
            : 'Pemasukan berhasil dicatat.');
    }

    public function storePencairan(Request $request)
    {
        $request->validate([
            'jumlah' => ['required', 'numeric', 'min:100000'],
            'bank_account_id' => ['required', 'exists:store_bank_accounts,bank_account_id'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $store = $user->ownedStores()->first();
        $wallet = $store?->wallet;

        if (! $wallet) {
            return back()->with('error', 'Dompet toko tidak ditemukan.');
        }

        $locked = (float) $wallet->withdrawals()
            ->where('status', Withdrawal::STATUS_PENDING)
            ->sum('jumlah');
        $available = (float) $wallet->saldo_tersedia - $locked;
        if ($available < (float) $request->jumlah) {
            return back()->with('error', 'Saldo tersedia tidak mencukupi (termasuk opsi pencairan yang sedang menunggu).');
        }

        $bankAccount = $store->bankAccounts()->findOrFail($request->bank_account_id);

        DB::transaction(function () use ($wallet, $store, $bankAccount, $request) {
            Withdrawal::create([
                'store_id' => $store->store_id,
                'wallet_id' => $wallet->wallet_id,
                'bank_account_id' => $bankAccount->bank_account_id,
                'jumlah' => $request->jumlah,
                'status' => Withdrawal::STATUS_PENDING,
                'diajukan_pada' => now(),
            ]);
        });

        NotificationService::sendToRole(
            Role::SUPER_ADMIN,
            Notification::TIPE_WALLET,
            'Pengajuan Pencairan Baru',
            sprintf('Toko "%s" mengajukan pencairan Rp %s.', $store->nama_toko, number_format((float) $request->jumlah, 0, ',', '.')),
            $user->user_id,
            route('superadmin.permintaan-penarikan')
        );
        Notification::fireSelf(Notification::TIPE_WALLET, 'Pencairan Diajukan', sprintf('Pengajuan pencairan Rp %s berhasil diajukan.', number_format((float) $request->jumlah, 0, ',', '.')), route('owner.keuangan'));

        return redirect()->route('owner.keuangan')->with('success', 'Permintaan pencairan berhasil diajukan.');
    }
}
