<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerTopup;
use App\Models\CustomerWallet;
use App\Models\CustomerWalletTransaction;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentProof;
use App\Models\PlatformBankAccount;
use App\Models\Role;
use App\Services\NotificationService;
use App\Support\CustomerWalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaldoController extends Controller
{
    protected const NOMINAL_CEPAT = [50000, 100000, 250000, 500000, 1000000];

    protected const MIN_NOMINAL = 10000;

    protected const MAX_NOMINAL = 100000000;

    public function index()
    {
        $this->expireOverdue();

        $user = Auth::user();
        $wallet = CustomerWalletService::walletFor($user);
        $saldo = CustomerWalletService::balance($user);

        $totalTopup = (float) CustomerTopup::where('user_id', $user->user_id)
            ->where('status', CustomerTopup::STATUS_TERVERIFIKASI)
            ->sum('jumlah');

        $totalBelanja = (float) CustomerWalletTransaction::where('customer_wallet_id', $wallet->customer_wallet_id)
            ->where('jenis_transaksi', CustomerWalletTransaction::JENIS_PEMBAYARAN_KELUAR)
            ->sum('jumlah');

        $transactions = CustomerWalletTransaction::where('customer_wallet_id', $wallet->customer_wallet_id)
            ->with(['topup', 'order'])
            ->orderByDesc('customer_wallet_transaction_id')
            ->paginate(12);

        $activeTopups = CustomerTopup::where('user_id', $user->user_id)
            ->with(['payment.paymentMethod', 'payment.account', 'payment.proofs'])
            ->whereIn('status', [
                CustomerTopup::STATUS_PENDING,
                CustomerTopup::STATUS_MENUNGGU_VERIFIKASI,
                CustomerTopup::STATUS_DITOLAK,
            ])
            ->orderByDesc('customer_topup_id')
            ->get();

        return view('customer.saldo.index', compact('saldo', 'totalTopup', 'totalBelanja', 'transactions', 'activeTopups'));
    }

    public function topup(Request $request)
    {
        $validated = $request->validate([
            'nominal' => ['required', 'integer', 'min:'.static::MIN_NOMINAL, 'max:'.static::MAX_NOMINAL],
        ], [
            'nominal.required' => 'Masukkan nominal topup.',
            'nominal.integer' => 'Nominal harus berupa angka.',
            'nominal.min' => 'Nominal minimal Rp '.number_format(static::MIN_NOMINAL, 0, ',', '.').'.',
            'nominal.max' => 'Nominal maksimal Rp '.number_format(static::MAX_NOMINAL, 0, ',', '.').'.',
        ]);

        $user = Auth::user();
        $jumlah = (float) $validated['nominal'];

        [$topup, $payment] = DB::transaction(function () use ($user, $jumlah) {
            $payment = Payment::create([
                'checkout_id' => null,
                'payment_method_id' => null,
                'jumlah' => $jumlah,
                'status' => Payment::STATUS_PENDING,
                'batas_waktu' => now()->addMinutes(1440),
            ]);

            $topup = CustomerTopup::create([
                'user_id' => $user->user_id,
                'payment_id' => $payment->payment_id,
                'jumlah' => $jumlah,
                'status' => CustomerTopup::STATUS_PENDING,
                'batas_waktu' => now()->addMinutes(1440),
            ]);

            $payment->update(['topup_id' => $topup->customer_topup_id]);

            return [$topup, $payment];
        });

        return redirect()->route('customer.saldo.topup.payment', $topup->customer_topup_id)
            ->with('toast', ['message' => 'Topup dibuat. Silakan bayar sesuai nominal.', 'icon' => 'task_alt']);
    }

    public function payment(Request $request, CustomerTopup $topup)
    {
        if ($topup->user_id !== Auth::id()) {
            abort(403);
        }

        $this->expireOverdue();

        $topup->load(['payment.paymentMethod', 'payment.account', 'payment.proofs']);

        $paymentMethods = PaymentMethod::with('accounts')
            ->where('status', PaymentMethod::STATUS_AKTIF)
            ->where('kode_metode', '!=', PaymentMethod::KODE_SALDO_AKUN)
            ->orderBy('payment_method_id')
            ->get();

        return view('customer.saldo.payment', compact('topup', 'paymentMethods'));
    }

    public function uploadTopupProof(Request $request, CustomerTopup $topup)
    {
        if ($topup->user_id !== Auth::id()) {
            abort(403);
        }

        $this->expireOverdue();

        if (! in_array($topup->status, [CustomerTopup::STATUS_PENDING, CustomerTopup::STATUS_DITOLAK, CustomerTopup::STATUS_MENUNGGU_VERIFIKASI], true)) {
            return back()->with('toast', ['message' => 'Topup sudah diproses.', 'icon' => 'gpp_maybe']);
        }

        $validated = $request->validate([
            'payment_method_id' => 'required|integer|exists:payment_methods,payment_method_id',
            'payment_account_id' => 'nullable|integer|exists:platform_bank_accounts,platform_bank_account_id',
            'bukti' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ], [
            'payment_method_id.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'bukti.required' => 'Pilih file bukti pembayaran.',
            'bukti.image' => 'File harus berupa gambar.',
        ]);

        $paymentMethod = PaymentMethod::where('payment_method_id', $validated['payment_method_id'])
            ->where('status', PaymentMethod::STATUS_AKTIF)
            ->first();
        if (! $paymentMethod) {
            return back()->with('toast', ['message' => 'Metode pembayaran tidak tersedia.', 'icon' => 'gpp_maybe']);
        }

        if (in_array($paymentMethod->kode_metode, ['ewallet', 'bank_transfer'], true) && empty($validated['payment_account_id'])) {
            return back()->with('toast', ['message' => 'Pilih akun/tujuan pembayaran terlebih dahulu.', 'icon' => 'gpp_maybe']);
        }

        $account = null;
        if (! empty($validated['payment_account_id'])) {
            $account = PlatformBankAccount::where('platform_bank_account_id', $validated['payment_account_id'])
                ->where('jenis', $paymentMethod->kode_metode)
                ->where('status', PlatformBankAccount::STATUS_AKTIF)
                ->first();
            if (! $account) {
                return back()->with('toast', ['message' => 'Tujuan pembayaran tidak cocok dengan metode dipilih.', 'icon' => 'gpp_maybe']);
            }
        }

        $payment = $topup->payment;
        $fileName = 'topup-'.$topup->customer_topup_id.'-'.time().'.'.$validated['bukti']->extension();
        $path = $validated['bukti']->storeAs('payment_proofs', $fileName, 'public');

        DB::transaction(function () use ($payment, $topup, $paymentMethod, $account, $path) {
            $updatePayload = [];
            if (is_null($payment->payment_method_id)) {
                $batas = $paymentMethod->batas_waktu_menit > 0 ? $paymentMethod->batas_waktu_menit : 1440;
                $updatePayload['payment_method_id'] = $paymentMethod->payment_method_id;
                $updatePayload['batas_waktu'] = now()->addMinutes($batas);
            }
            if ($account) {
                $updatePayload['payment_account_id'] = $account->platform_bank_account_id;
            }
            if ($updatePayload) {
                $payment->update($updatePayload);
            }
            PaymentProof::create([
                'payment_id' => $payment->payment_id,
                'file_bukti' => $path,
                'uploaded_at' => now(),
            ]);
            $payment->update(['status' => Payment::STATUS_MENUNGGU_VERIFIKASI]);
            $topup->update([
                'status' => CustomerTopup::STATUS_MENUNGGU_VERIFIKASI,
                'batas_waktu' => $payment->batas_waktu,
            ]);
        });

        NotificationService::fire(
            Auth::id(),
            Notification::TIPE_PEMBAYARAN,
            'Bukti Topup Diunggah',
            'Bukti topup saldo Rp '.number_format((float) $topup->jumlah, 0, ',', '.').' sedang diverifikasi.',
        );

        NotificationService::sendToRole(
            Role::SUPER_ADMIN,
            Notification::TIPE_WALLET,
            'Bukti Topup Saldo Baru',
            'Customer mengunggah bukti topup Rp '.number_format((float) $topup->jumlah, 0, ',', '.').'. Segera verifikasi.',
            Auth::id(),
            route('superadmin.verifikasi-topup')
        );

        return redirect()->route('customer.saldo.topup.selesai', $topup->customer_topup_id)
            ->with('toast', ['message' => 'Bukti topup diunggah. Menunggu verifikasi Super Admin.', 'icon' => 'task_alt']);
    }

    public function selesai(CustomerTopup $topup)
    {
        if (! Auth::check()) {
            return redirect()->route('login', ['redirect' => route('customer.saldo.topup.selesai', $topup->customer_topup_id)]);
        }
        if (Auth::user()->role?->nama_role !== Role::CUSTOMER) {
            abort(403);
        }
        if ($topup->user_id !== Auth::id()) {
            abort(403);
        }

        $this->expireOverdue();
        $topup->load(['payment.paymentMethod', 'payment.account']);

        return view('customer.saldo.selesai', compact('topup'));
    }

    public function paymentStatus(CustomerTopup $topup)
    {
        if (! Auth::check()) {
            return response()->json(['unauthenticated' => true], 401);
        }

        if ($topup->user_id !== Auth::id()) {
            abort(403);
        }

        $this->expireOverdue();
        $topup->refresh();

        return response()->json([
            'status' => $topup->status,
            'verified' => $topup->status === CustomerTopup::STATUS_TERVERIFIKASI,
        ]);
    }

    /**
     * Mark topup yang batas waktunya lewat (status pending) sebagai kadaluarsa.
     */
    protected function expireOverdue(): void
    {
        CustomerTopup::where('status', CustomerTopup::STATUS_PENDING)
            ->where('batas_waktu', '<', now())
            ->get()
            ->each(function (CustomerTopup $topup) {
                DB::transaction(function () use ($topup) {
                    $topup->update(['status' => CustomerTopup::STATUS_KADALUARSA]);
                    $topup->payment()->update(['status' => Payment::STATUS_KADALUARSA]);
                });
            });
    }
}