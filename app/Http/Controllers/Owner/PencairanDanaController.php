<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                'banks' => \App\Models\Bank::where('status', 'aktif')->orderBy('nama_bank')->get(),
                'store' => null,
                'available' => 0,
                'locked' => 0,
                'totalDicairkan' => 0,
            ]);
        }
        $wallet = $store->wallet;
        if (! $wallet) {
            $wallet = \App\Models\Wallet::create(['store_id'=>$store->store_id,'saldo_tersedia'=>0,'saldo_tertahan'=>0]);
            $store->setRelation('wallet', $wallet);
        }
        $banks = \App\Models\Bank::where('status', 'aktif')->orderBy('nama_bank')->get();
        $withdrawals = $wallet->withdrawals()->with(['bankAccount.bank', 'bank'])->orderByDesc('diajukan_pada')->paginate(10);
        $locked = (float) $wallet->withdrawals()
            ->where('status', Withdrawal::STATUS_PENDING)
            ->sum('jumlah');
        $available = max(0, (float) $wallet->saldo_tersedia - $locked);
        $totalDicairkan = (float) $wallet->withdrawals()
            ->where('status', Withdrawal::STATUS_DIBAYAR)
            ->sum('jumlah');

        return view('Owner.pencairan-dana.index', compact('wallet', 'withdrawals', 'banks', 'store', 'available', 'locked', 'totalDicairkan'));
    }

    public function store(Request $request)
    {
        $store = OwnerContext::currentStore();
        if (! $store || ! $store->wallet) {
            return back()->with('error', 'Anda belum memiliki toko/wallet.');
        }
        $data = $request->validate([
            'jumlah' => ['required', 'numeric', 'min:100000'],
            'tipe_tujuan' => ['required', 'in:bank,e-wallet'],
            'bank_id' => ['required_if:tipe_tujuan,bank', 'nullable', 'integer', 'exists:banks,bank_id'],
            'penyedia' => ['required_if:tipe_tujuan,e-wallet', 'nullable', 'string', 'max:100'],
            'nomor_tujuan' => ['required', 'string', 'max:50'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'tipe_tujuan.required' => 'Pilih tipe tujuan pencairan.',
            'bank_id.required_if' => 'Pilih bank tujuan.',
            'penyedia.required_if' => 'Pilih penyedia e-wallet.',
            'nomor_tujuan.required' => 'Masukkan nomor tujuan.',
        ]);
        $wallet = $store->wallet;
        $locked = (float) $wallet->withdrawals()
            ->where('status', Withdrawal::STATUS_PENDING)
            ->sum('jumlah');
        $available = (float) $wallet->saldo_tersedia - $locked;
        if ($available < (float) $data['jumlah']) {
            return back()->with('error', 'Saldo tidak cukup (termasuk opsi pencairan yang sedang menunggu).');
        }
        DB::transaction(function () use ($wallet, $data, $store) {
            Withdrawal::create([
                'store_id' => $store->store_id,
                'wallet_id' => $wallet->wallet_id,
                'tipe_tujuan' => $data['tipe_tujuan'],
                'bank_id' => $data['tipe_tujuan'] === 'bank' ? ($data['bank_id'] ?? null) : null,
                'penyedia' => $data['tipe_tujuan'] === 'e-wallet' ? ($data['penyedia'] ?? null) : null,
                'nomor_tujuan' => $data['nomor_tujuan'],
                'jumlah' => $data['jumlah'],
                'status' => Withdrawal::STATUS_PENDING,
                'diajukan_pada' => now(),
            ]);
        });

        $sa = \App\Models\User::whereHas('role', fn ($q) => $q->where('nama_role', 'Super Admin'))
            ->where('status', \App\Models\User::STATUS_AKTIF)
            ->first();
        if ($sa) {
            \App\Models\Notification::create([
                'user_id' => $sa->user_id,
                'aktor_id' => $request->user()->user_id,
                'tipe' => \App\Models\Notification::TIPE_WALLET,
                'judul' => 'Pengajuan Pencairan Baru',
                'pesan' => sprintf('Toko "%s" mengajukan pencairan Rp %s.', $store->nama_toko, number_format((float) $data['jumlah'], 0, ',', '.')),
                'url' => route('superadmin.permintaan-penarikan'),
            ]);
        }
        \App\Models\Notification::fireSelf(\App\Models\Notification::TIPE_WALLET, 'Pencairan Diajukan', sprintf('Pengajuan pencairan Rp %s berhasil diajukan.', number_format((float) $data['jumlah'], 0, ',', '.')), route('owner.pencairan-dana'));

        return back()->with('success', 'Pengajuan pencairan berhasil.');
    }
}

