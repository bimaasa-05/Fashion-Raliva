<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\StoreExpense;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SaldoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $store = $user->ownedStores()->first();

        // Jika Owner belum punya toko / wallet, tampilkan halaman kosong yang ramah.
        if (! $store || ! $store->wallet) {
            return view('Owner.saldo.index', [
                'wallet' => null,
                'mutations' => collect(),
                'withdrawals' => collect(),
                'refunds' => collect(),
                'summary' => [
                    'pemasukan' => 0,
                    'pengeluaran' => 0,
                    'bersih' => 0,
                ],
                'chart' => collect(),
            ]);
        }

        $wallet = $store->wallet;

        $bankAccounts = $store->bankAccounts()->with('bank')->get();

        $totalDicairkan = (float) $wallet->withdrawals()
            ->where('status', Withdrawal::STATUS_DIBAYAR)
            ->sum('jumlah');

        $mutations = $wallet->transactions()
            ->orderByDesc('created_at')
            ->paginate(10);

        $withdrawals = $wallet->withdrawals()
            ->with('bankAccount.bank')
            ->orderByDesc('diajukan_pada')
            ->get();

        $refunds = Refund::whereIn('order_id', function ($q) use ($store) {
                $q->select('order_id')->from('orders')->where('store_id', $store->store_id);
            })
            ->orderByDesc('diajukan_pada')
            ->get();

        // Ringkasan bulan ini (dari awal bulan berjalan).
        $startOfMonth = Carbon::now()->startOfMonth();
        $monthTx = $wallet->transactions()
            ->where('created_at', '>=', $startOfMonth)
            ->get();

        $pemasukan = $monthTx->whereIn('jenis_transaksi', [
            WalletTransaction::JENIS_PENJUALAN_MASUK,
            WalletTransaction::JENIS_KOMISI_MASUK,
        ])->sum('jumlah');

        $pengeluaran = $monthTx->whereNotIn('jenis_transaksi', [
            WalletTransaction::JENIS_PENJUALAN_MASUK,
            WalletTransaction::JENIS_KOMISI_MASUK,
        ])->sum(function ($t) {
            return abs((float) $t->jumlah);
        });

        $summary = [
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'bersih' => $pemasukan - $pengeluaran,
        ];

        // Data tren 6 bulan terakhir (saldo tersedia di akhir tiap bulan).
        $chart = collect(range(5, 0))->map(function ($i) use ($wallet) {
            $month = Carbon::now()->subMonths($i);
            $saldoAkhir = (float) $wallet->transactions()
                ->where('created_at', '<=', $month->copy()->endOfMonth())
                ->orderByDesc('created_at')
                ->value('saldo_sesudah');

            return [
                'label' => $month->translatedFormat('M'),
                'saldo' => $saldoAkhir,
            ];
        });

        $expenses = StoreExpense::where('store_id', $store->store_id)
            ->orderByDesc('tanggal')
            ->get();

        // Estimasi margin (asumsi HPP 60% revenue, pajak 25% laba, tanpa D&A/bunga).
        $revenue = $pemasukan;
        $hpp = $revenue * 0.60;
        $grossProfit = $revenue - $hpp;
        $operasional = (float) $expenses->where('tanggal', '>=', $startOfMonth)->sum('nominal');
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

        return view('Owner.saldo.index', compact(
            'wallet', 'bankAccounts', 'totalDicairkan',
            'mutations', 'withdrawals', 'refunds', 'summary', 'chart',
            'expenses', 'margin'
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

        StoreExpense::create([
            'store_id' => $store->store_id,
            'nama' => $validated['nama'],
            'kategori' => $validated['kategori'],
            'nominal' => $validated['nominal'],
            'tanggal' => $validated['tanggal'],
        ]);

        return redirect()->route('owner.saldo')->with('success', 'Pengeluaran berhasil dicatat.');
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

        if ((float) $wallet->saldo_tersedia < (float) $request->jumlah) {
            return back()->with('error', 'Saldo tersedia tidak mencukupi untuk pencairan ini.');
        }

        $bankAccount = $store->bankAccounts()->findOrFail($request->bank_account_id);

        DB::transaction(function () use ($wallet, $store, $bankAccount, $request) {
            $saldoSebelum = (float) $wallet->saldo_tersedia;
            $wallet->decrement('saldo_tersedia', $request->jumlah);

            $withdrawal = Withdrawal::create([
                'store_id' => $store->store_id,
                'wallet_id' => $wallet->wallet_id,
                'bank_account_id' => $bankAccount->bank_account_id,
                'jumlah' => $request->jumlah,
                'status' => Withdrawal::STATUS_PENDING,
                'diajukan_pada' => now(),
            ]);

            WalletTransaction::create([
                'wallet_id' => $wallet->wallet_id,
                'withdrawal_id' => $withdrawal->withdrawal_id,
                'jenis_transaksi' => WalletTransaction::JENIS_WITHDRAWAL,
                'jumlah' => -$request->jumlah,
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSebelum - (float) $request->jumlah,
                'keterangan' => 'Pencairan dana ke ' . ($bankAccount->bank->nama_bank ?? 'Bank') . ' ' . $bankAccount->nomor_rekening,
            ]);
        });

        return redirect()->route('owner.saldo', ['#pencairan'])
            ->with('success', 'Permintaan pencairan berhasil diajukan.');
    }
}
