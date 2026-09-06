<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class PencairanDanaController extends Controller
{
    public function index(Request $request)
    {
        $store = OwnerContext::currentStore();
        if (! $store) {
            $wallet = new \App\Models\Wallet(['saldo_tersedia'=>0,'saldo_tertahan'=>0]);
            return view('Owner.pencairan-dana.index', [
                'wallet' => $wallet,
                'withdrawals' => collect(),
                'bankAccounts' => collect(),
                'store' => null,
            ]);
        }
        $wallet = $store->wallet;
        if (! $wallet) {
            $wallet = \App\Models\Wallet::create(['store_id'=>$store->store_id,'saldo_tersedia'=>0,'saldo_tertahan'=>0]);
            $store->setRelation('wallet', $wallet);
        }
        $bankAccounts = $store->bankAccounts()->with('bank')->get();
        $withdrawals = $wallet->withdrawals()->with('bankAccount.bank')->orderByDesc('diajukan_pada')->paginate(10);

        return view('Owner.pencairan-dana.index', compact('wallet', 'withdrawals', 'bankAccounts', 'store'));
    }

    public function store(Request $request)
    {
        $store = OwnerContext::currentStore();
        if (! $store || ! $store->wallet) {
            return back()->with('error', 'Anda belum memiliki toko/wallet.');
        }
        $data = $request->validate([
            'jumlah' => ['required', 'numeric', 'min:100000'],
            'bank_account_id' => ['required', 'exists:store_bank_accounts,store_bank_account_id'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);
        $wallet = $store->wallet;
        if ((float) $wallet->saldo_tersedia < (float) $data['jumlah']) {
            return back()->with('error', 'Saldo tidak cukup.');
        }
        $bank = $store->bankAccounts()->findOrFail($data['bank_account_id']);
        \Illuminate\Support\Facades\DB::transaction(function () use ($wallet, $bank, $data, $store) {
            $wallet->decrement('saldo_tersedia', $data['jumlah']);
            Withdrawal::create([
                'wallet_id' => $wallet->wallet_id,
                'bank_account_id' => $bank->store_bank_account_id,
                'jumlah' => $data['jumlah'],
                'status' => Withdrawal::STATUS_PENDING,
                'diajukan_pada' => now(),
                'catatan' => $data['catatan'] ?? null,
            ]);
            \App\Models\WalletTransaction::create([
                'wallet_id' => $wallet->wallet_id,
                'jenis_transaksi' => \App\Models\WalletTransaction::JENIS_WITHDRAWAL,
                'jumlah' => $data['jumlah'],
                'saldo_sebelum' => (float) $wallet->saldo_tersedia + (float) $data['jumlah'],
                'saldo_sesudah' => (float) $wallet->saldo_tersedia,
                'keterangan' => 'Pengajuan pencairan',
            ]);
        });

        return back()->with('success', 'Pengajuan pencairan berhasil.');
    }
}

