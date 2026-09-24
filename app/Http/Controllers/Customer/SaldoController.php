<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\CustomerTopup;
use App\Models\CustomerWallet;
use App\Models\CustomerWalletTransaction;
use App\Models\CustomerWithdrawal;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentProof;
use App\Models\PlatformBankAccount;
use App\Models\Role;
use App\Models\Setting;
use App\Services\NotificationService;
use App\Support\CustomerWalletService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaldoController extends Controller
{
    protected const NOMINAL_CEPAT = [50000, 100000, 250000, 500000, 1000000];

    protected const MIN_NOMINAL = 10000;

    protected const MAX_NOMINAL = 100000000;

    protected const MIN_TARIK = 50000;

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

        $since = Carbon::now()->subYear()->startOfDay();

        $transaksiMasuk = $wallet->transactions()
            ->whereIn('jenis_transaksi', [
                CustomerWalletTransaction::JENIS_TOPUP,
                CustomerWalletTransaction::JENIS_REFUND_MASUK,
            ])
            ->where('created_at', '>=', $since)
            ->get(['jumlah', 'created_at']);

        $transaksiKeluar = $wallet->transactions()
            ->where('jenis_transaksi', CustomerWalletTransaction::JENIS_PEMBAYARAN_KELUAR)
            ->where('created_at', '>=', $since)
            ->get(['jumlah', 'created_at']);

        $masukBulanan = $transaksiMasuk->groupBy(fn ($t) => $t->created_at->format('Y-m'));
        $keluarBulanan = $transaksiKeluar->groupBy(fn ($t) => $t->created_at->format('Y-m'));
        $masukHarian = $transaksiMasuk->groupBy(fn ($t) => $t->created_at->format('Y-m-d'));
        $keluarHarian = $transaksiKeluar->groupBy(fn ($t) => $t->created_at->format('Y-m-d'));

        $ranges = [];
        $rangeDefs = [
            '1tahun' => ['label' => '1 Tahun', 'bulan' => 12, 'hari' => null],
            '6bulan' => ['label' => '6 Bulan', 'bulan' => 6, 'hari' => null],
            '3bulan' => ['label' => '3 Bulan', 'bulan' => 3, 'hari' => null],
            '1minggu' => ['label' => '1 Minggu', 'bulan' => null, 'hari' => 7],
        ];

        foreach ($rangeDefs as $key => $def) {
            $inSeries = [];
            $outSeries = [];

            if ($def['hari'] !== null) {
                for ($i = $def['hari'] - 1; $i >= 0; $i--) {
                    $d = Carbon::now()->subDays($i);
                    $k = $d->format('Y-m-d');
                    $inSeries[] = ['label' => $d->translatedFormat('j'), 'value' => (float) ($masukHarian->get($k) ?? collect())->sum('jumlah')];
                    $outSeries[] = ['label' => $d->translatedFormat('j'), 'value' => abs((float) ($keluarHarian->get($k) ?? collect())->sum('jumlah'))];
                }
            } else {
                for ($i = $def['bulan'] - 1; $i >= 0; $i--) {
                    $m = Carbon::now()->subMonths($i);
                    $k = $m->format('Y-m');
                    $inSeries[] = ['label' => $m->translatedFormat('M'), 'value' => (float) ($masukBulanan->get($k) ?? collect())->sum('jumlah')];
                    $outSeries[] = ['label' => $m->translatedFormat('M'), 'value' => abs((float) ($keluarBulanan->get($k) ?? collect())->sum('jumlah'))];
                }
            }

            $ranges[$key] = [
                'label' => $def['label'],
                'bulanan' => $def['hari'] === null,
                'pemasukan' => $inSeries,
                'pengeluaran' => $outSeries,
            ];
        }

        $activeTopups = CustomerTopup::where('user_id', $user->user_id)
            ->with(['payment.paymentMethod', 'payment.account', 'payment.proofs'])
            ->whereIn('status', [
                CustomerTopup::STATUS_PENDING,
                CustomerTopup::STATUS_MENUNGGU_VERIFIKASI,
                CustomerTopup::STATUS_DITOLAK,
            ])
            ->orderByDesc('customer_topup_id')
            ->get();

        $riwayatTarik = CustomerWithdrawal::where('user_id', $user->user_id)
            ->with('bank')
            ->orderByDesc('customer_withdrawal_id')
            ->limit(10)
            ->get();

        return view('customer.saldo.index', compact('saldo', 'totalTopup', 'totalBelanja', 'transactions', 'activeTopups', 'ranges', 'riwayatTarik'));
    }

    public function isiSaldo()
    {
        $nominalCepat = static::NOMINAL_CEPAT;
        $minNominal = static::MIN_NOMINAL;
        $maxNominal = static::MAX_NOMINAL;

        return view('customer.saldo.isi-saldo', compact('nominalCepat', 'minNominal', 'maxNominal'));
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

    /**
     * Batalkan topup milik sendiri (hanya pending/ditolak).
     */
    public function batalkan(CustomerTopup $topup)
    {
        if ($topup->user_id !== Auth::id()) {
            abort(403);
        }

        if (! in_array($topup->status, [CustomerTopup::STATUS_PENDING, CustomerTopup::STATUS_DITOLAK], true)) {
            return back()->with('toast', ['message' => 'Topup ini sudah tidak bisa dibatalkan.', 'icon' => 'gpp_maybe']);
        }

        DB::transaction(function () use ($topup) {
            $topup->update(['status' => CustomerTopup::STATUS_DIBATALKAN]);
            $topup->payment()->update(['status' => Payment::STATUS_KADALUARSA]);
        });

        return redirect()->route('customer.saldo')
            ->with('toast', ['message' => 'Topup Rp '.number_format((float) $topup->jumlah, 0, ',', '.').' dibatalkan.', 'icon' => 'task_alt']);
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

    /**
     * Form pengajuan penarikan saldo (langkah 1).
     */
    public function tarik()
    {
        $user = Auth::user();
        $saldo = CustomerWalletService::balance($user);
        $feePersen = min(100, max(0, (float) Setting::get(Setting::BIAYA_PENARIKAN_SALDO, '0')));
        $minTarik = static::MIN_TARIK;
        $banks = Bank::where('status', 'aktif')->whereRaw('LOWER(kode_bank) != ?', ['bsi'])->orderBy('nama_bank')->get();

        return view('customer.saldo.tarik', compact('saldo', 'feePersen', 'minTarik', 'banks'));
    }

    /**
     * Simpan pengajuan penarikan + tahan (hold) saldo.
     */
    public function storeTarik(Request $request)
    {
        $validated = $request->validate([
            'nominal' => ['required', 'integer', 'min:' . static::MIN_TARIK, 'max:' . static::MAX_NOMINAL],
            'tipe_tujuan' => ['required', 'in:' . CustomerWithdrawal::TIPE_BANK . ',' . CustomerWithdrawal::TIPE_EWALLET],
            'bank_id' => ['required_if:tipe_tujuan,' . CustomerWithdrawal::TIPE_BANK, 'nullable', 'integer', 'exists:banks,bank_id'],
            'penyedia' => ['required_if:tipe_tujuan,' . CustomerWithdrawal::TIPE_EWALLET, 'nullable', 'string', 'max:100'],
            'nomor_tujuan' => ['required', 'string', 'max:50'],
            'nama_pemilik' => ['nullable', 'string', 'max:150'],
        ], [
            'nominal.required' => 'Masukkan nominal penarikan.',
            'nominal.integer' => 'Nominal harus berupa angka.',
            'nominal.min' => 'Nominal minimal Rp ' . number_format(static::MIN_TARIK, 0, ',', '.') . '.',
            'nominal.max' => 'Nominal maksimal Rp ' . number_format(static::MAX_NOMINAL, 0, ',', '.') . '.',
            'tipe_tujuan.required' => 'Pilih tipe tujuan pencairan.',
            'tipe_tujuan.in' => 'Tipe tujuan tidak valid.',
            'bank_id.required_if' => 'Pilih bank tujuan.',
            'bank_id.exists' => 'Bank tujuan tidak valid.',
            'penyedia.required_if' => 'Masukkan penyedia e-wallet.',
            'nomor_tujuan.required' => 'Masukkan nomor tujuan.',
        ]);

        $user = Auth::user();
        $jumlah = (float) $validated['nominal'];
        $feePersen = min(100, max(0, (float) Setting::get(Setting::BIAYA_PENARIKAN_SALDO, '0')));
        $fee = (float) round($jumlah * $feePersen / 100);
        $bersih = $jumlah - $fee;

        if ($validated['tipe_tujuan'] === CustomerWithdrawal::TIPE_BANK && ! empty($validated['bank_id'])) {
            $bankTujuan = Bank::where('bank_id', $validated['bank_id'])->first();
            if (! $bankTujuan || strtolower($bankTujuan->kode_bank ?? '') === 'bsi') {
                return back()
                    ->with('toast', ['message' => 'Bank tujuan tidak tersedia untuk penarikan.', 'icon' => 'gpp_maybe'])
                    ->withInput();
            }
        }

        if ($bersih <= 0) {
            return back()
                ->with('toast', ['message' => 'Nominal harus lebih besar dari biaya platform ' . rtrim(rtrim(number_format($feePersen, 2, ',', '.'), '0'), ',') . '%.', 'icon' => 'gpp_maybe'])
                ->withInput();
        }

        if (CustomerWalletService::balance($user) < $jumlah) {
            return back()
                ->with('toast', ['message' => 'Saldo akun tidak mencukupi untuk penarikan ini.', 'icon' => 'gpp_maybe'])
                ->withInput();
        }

        $penarikan = DB::transaction(function () use ($user, $validated, $jumlah, $fee, $bersih) {
            $penarikan = CustomerWithdrawal::create([
                'user_id' => $user->user_id,
                'jumlah' => $jumlah,
                'fee' => $fee,
                'jumlah_bersih' => $bersih,
                'tipe_tujuan' => $validated['tipe_tujuan'],
                'bank_id' => $validated['tipe_tujuan'] === CustomerWithdrawal::TIPE_BANK ? ($validated['bank_id'] ?? null) : null,
                'penyedia' => $validated['tipe_tujuan'] === CustomerWithdrawal::TIPE_EWALLET ? ($validated['penyedia'] ?? null) : null,
                'nomor_tujuan' => $validated['nomor_tujuan'],
                'nama_pemilik' => $validated['nama_pemilik'] ?? null,
                'status' => CustomerWithdrawal::STATUS_PENDING,
                'diajukan_pada' => now(),
            ]);

            CustomerWalletService::debitForWithdrawal($user, $penarikan, $jumlah);

            return $penarikan;
        });

        NotificationService::fire(
            Auth::id(),
            Notification::TIPE_PEMBAYARAN,
            'Penarikan Saldo Diajukan',
            'Pengajuan penarikan saldo Rp ' . number_format($jumlah, 0, ',', '.') . ' sedang diverifikasi.',
        );

        NotificationService::sendToRole(
            Role::SUPER_ADMIN,
            Notification::TIPE_WALLET,
            'Pengajuan Penarikan Saldo Baru',
            'Customer mengajukan penarikan saldo Rp ' . number_format($jumlah, 0, ',', '.') . ' (bersih Rp ' . number_format($bersih, 0, ',', '.') . '). Segera verifikasi.',
            Auth::id(),
            route('superadmin.verifikasi-penarikan-saldo')
        );

        return redirect()->route('customer.saldo.tarik.show', $penarikan->customer_withdrawal_id)
            ->with('toast', ['message' => 'Pengajuan penarikan dibuat. Menunggu verifikasi Super Admin.', 'icon' => 'task_alt']);
    }

    /**
     * Halaman status penarikan (langkah 2 Verifikasi / 3 Selesai).
     */
    public function penarikan(CustomerWithdrawal $penarikan)
    {
        if (! Auth::check()) {
            return redirect()->route('login', ['redirect' => route('customer.saldo.tarik.show', $penarikan->customer_withdrawal_id)]);
        }
        if (Auth::user()->role?->nama_role !== Role::CUSTOMER) {
            abort(403);
        }
        if ($penarikan->user_id !== Auth::id()) {
            abort(403);
        }

        $penarikan->load('bank');

        return view('customer.saldo.penarikan', compact('penarikan'));
    }

    public function penarikanStatus(CustomerWithdrawal $penarikan)
    {
        if (! Auth::check()) {
            return response()->json(['unauthenticated' => true], 401);
        }

        if ($penarikan->user_id !== Auth::id()) {
            abort(403);
        }

        $penarikan->refresh();

        return response()->json([
            'status' => $penarikan->status,
            'done' => in_array($penarikan->status, [
                CustomerWithdrawal::STATUS_DIBAYAR,
                CustomerWithdrawal::STATUS_DITOLAK,
                CustomerWithdrawal::STATUS_DIBATALKAN,
            ], true),
        ]);
    }

    /**
     * Batalkan penarikan milik sendiri (hanya pending) + kembalikan hold.
     */
    public function batalkanTarik(CustomerWithdrawal $penarikan)
    {
        if ($penarikan->user_id !== Auth::id()) {
            abort(403);
        }

        if ($penarikan->status !== CustomerWithdrawal::STATUS_PENDING) {
            return back()->with('toast', ['message' => 'Penarikan ini sudah tidak bisa dibatalkan.', 'icon' => 'gpp_maybe']);
        }

        DB::transaction(function () use ($penarikan) {
            $penarikan->update(['status' => CustomerWithdrawal::STATUS_DIBATALKAN]);
            CustomerWalletService::creditWithdrawalRefund(Auth::user(), $penarikan, (float) $penarikan->jumlah);
        });

        return redirect()->route('customer.saldo')
            ->with('toast', ['message' => 'Penarikan Rp ' . number_format((float) $penarikan->jumlah, 0, ',', '.') . ' dibatalkan, saldo dikembalikan.', 'icon' => 'task_alt']);
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