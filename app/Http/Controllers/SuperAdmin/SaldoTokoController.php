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
            ->paginate(20)->withQueryString();

        $totalTersedia = (float) Wallet::sum('saldo_tersedia');
        $totalTertahan = (float) Wallet::sum('saldo_tertahan');
        $jumlahToko = Wallet::count();

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
