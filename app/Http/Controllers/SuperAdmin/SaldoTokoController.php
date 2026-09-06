<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class SaldoTokoController extends Controller
{
    public function index()
    {
        $wallets = Wallet::with('store:store_id,nama_toko')
            ->orderByDesc('saldo_tersedia')
            ->get();

        $totalTersedia = (float) $wallets->sum('saldo_tersedia');
        $totalTertahan = (float) $wallets->sum('saldo_tertahan');
        $jumlahToko = $wallets->count();

        $transactions = WalletTransaction::with(['wallet.store:store_id,nama_toko'])
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        return view('SuperAdmin.saldo-toko.index', [
            'wallets' => $wallets,
            'totalTersedia' => $totalTersedia,
            'totalTertahan' => $totalTertahan,
            'jumlahToko' => $jumlahToko,
            'transactions' => $transactions,
        ]);
    }
}
