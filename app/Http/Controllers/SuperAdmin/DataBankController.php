<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Notification;
use App\Models\PlatformBankAccount;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DataBankController extends Controller
{
    public function index()
    {
        $banks = Bank::query()
            ->with('platformBankAccounts')
            ->orderBy('nama_bank')
            ->get();

        $ewallets = PlatformBankAccount::where('jenis', PlatformBankAccount::JENIS_EWALLET)
            ->orderBy('urutan')
            ->orderBy('platform_bank_account_id')
            ->get();

        $qris = PlatformBankAccount::where('jenis', PlatformBankAccount::JENIS_QRIS)
            ->orderBy('urutan')
            ->first();

        $totalRekening = PlatformBankAccount::count();

        return view('SuperAdmin.data-bank.index', [
            'banks' => $banks,
            'ewallets' => $ewallets,
            'qris' => $qris,
            'stats' => [
                'total' => $banks->count(),
                'aktif' => $banks->where('status', Bank::STATUS_AKTIF)->count(),
                'rekening' => $totalRekening,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_bank' => 'required|string|max:100',
            'kode_bank' => 'required|string|max:20|unique:banks,kode_bank',
            'nomor_rekening' => 'required|string|max:50',
            'nama_pemilik' => 'required|string|max:150',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_bank.required' => 'Nama bank wajib diisi.',
            'nama_bank.max' => 'Nama bank maksimal 100 karakter.',
            'kode_bank.required' => 'Kode bank wajib diisi.',
            'kode_bank.unique' => 'Kode bank sudah digunakan.',
            'kode_bank.max' => 'Kode bank maksimal 20 karakter.',
            'nomor_rekening.required' => 'Nomor rekening wajib diisi.',
            'nomor_rekening.max' => 'Nomor rekening maksimal 50 karakter.',
            'nama_pemilik.required' => 'Nama pemilik rekening wajib diisi.',
            'nama_pemilik.max' => 'Nama pemilik maksimal 150 karakter.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $bank = DB::transaction(function () use ($data) {
            $bank = Bank::create([
                'nama_bank' => $data['nama_bank'],
                'kode_bank' => $data['kode_bank'],
                'status' => $data['status'],
            ]);

            PlatformBankAccount::create([
                'bank_id' => $bank->bank_id,
                'jenis' => PlatformBankAccount::JENIS_BANK_TRANSFER,
                'nama' => 'Bank ' . strtoupper($data['kode_bank']),
                'kode' => $data['kode_bank'],
                'deskripsi' => 'Transfer ke rekening ' . $data['nama_bank'] . ' di bawah ini, lalu unggah buktinya.',
                'nomor_rekening' => $data['nomor_rekening'],
                'nama_pemilik' => $data['nama_pemilik'],
                'status' => $data['status'],
            ]);

            ActivityLogger::log(
                'bank.create',
                Bank::class,
                $bank->bank_id,
                null,
                ['nama_bank' => $bank->nama_bank, 'kode_bank' => $bank->kode_bank, 'nomor_rekening' => $data['nomor_rekening']],
                sprintf('Menambahkan bank "%s" — rekening %s a.n. %s.', $bank->nama_bank, $data['nomor_rekening'], $data['nama_pemilik'])
            );

            Notification::fireSelf(Notification::TIPE_SISTEM, 'Bank Ditambahkan', 'Bank "'.$bank->nama_bank.'" ditambahkan.', route('superadmin.data-bank'));

            return $bank;
        });

        return back()->with('toast', [
            'message' => 'Bank "'.$bank->nama_bank.'" berhasil ditambahkan.',
            'icon' => 'task_alt',
        ]);
    }

    public function update(Request $request, Bank $bank)
    {
        $data = $request->validate([
            'nama_bank' => 'required|string|max:100',
            'kode_bank' => 'required|string|max:20|unique:banks,kode_bank,'.$bank->bank_id.',bank_id',
            'nomor_rekening' => 'required|string|max:50',
            'nama_pemilik' => 'required|string|max:150',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_bank.required' => 'Nama bank wajib diisi.',
            'nama_bank.max' => 'Nama bank maksimal 100 karakter.',
            'kode_bank.required' => 'Kode bank wajib diisi.',
            'kode_bank.unique' => 'Kode bank sudah digunakan.',
            'nomor_rekening.required' => 'Nomor rekening wajib diisi.',
            'nama_pemilik.required' => 'Nama pemilik rekening wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $lama = $bank->only(['nama_bank', 'kode_bank', 'status']);
        $rekeningLama = $bank->platformBankAccounts->first();

        DB::transaction(function () use ($bank, $data, $lama, $rekeningLama) {
            $bank->update([
                'nama_bank' => $data['nama_bank'],
                'kode_bank' => $data['kode_bank'],
                'status' => $data['status'],
            ]);

            if ($rekeningLama) {
                $rekeningLama->update([
                    'jenis' => PlatformBankAccount::JENIS_BANK_TRANSFER,
                    'nama' => 'Bank ' . strtoupper($data['kode_bank']),
                    'kode' => $data['kode_bank'],
                    'deskripsi' => 'Transfer ke rekening ' . $data['nama_bank'] . ' di bawah ini, lalu unggah buktinya.',
                    'nomor_rekening' => $data['nomor_rekening'],
                    'nama_pemilik' => $data['nama_pemilik'],
                    'status' => $data['status'],
                ]);
            } else {
                PlatformBankAccount::create([
                    'bank_id' => $bank->bank_id,
                    'jenis' => PlatformBankAccount::JENIS_BANK_TRANSFER,
                    'nama' => 'Bank ' . strtoupper($data['kode_bank']),
                    'kode' => $data['kode_bank'],
                    'deskripsi' => 'Transfer ke rekening ' . $data['nama_bank'] . ' di bawah ini, lalu unggah buktinya.',
                    'nomor_rekening' => $data['nomor_rekening'],
                    'nama_pemilik' => $data['nama_pemilik'],
                    'status' => $data['status'],
                ]);
            }

            ActivityLogger::log(
                'bank.update',
                Bank::class,
                $bank->bank_id,
                $lama,
                ['nama_bank' => $bank->nama_bank, 'kode_bank' => $bank->kode_bank, 'nomor_rekening' => $data['nomor_rekening']],
                sprintf('Mengubah bank "%s" → "%s".', $lama['nama_bank'], $bank->nama_bank)
            );

            Notification::fireSelf(Notification::TIPE_SISTEM, 'Bank Diubah', 'Bank "'.$bank->nama_bank.'" diperbarui.', route('superadmin.data-bank'));
        });

        return back()->with('toast', [
            'message' => 'Perubahan bank "'.$bank->nama_bank.'" berhasil disimpan.',
            'icon' => 'task_alt',
        ]);
    }

    public function hapus(Request $request, Bank $bank)
    {
        $adaTransaksi = $bank->storeBankAccounts()->exists();

        if ($adaTransaksi) {
            return back()->with('toast', [
                'message' => 'Hapus dibatalkan — bank "'.$bank->nama_bank.'" masih memiliki rekening toko yang terdaftar.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $bank->only(['nama_bank', 'kode_bank', 'status']);
        $rekening = $bank->platformBankAccounts->pluck('nomor_rekening')->implode(', ');

        DB::transaction(function () use ($bank, $lama, $rekening) {
            ActivityLogger::log(
                'bank.delete',
                Bank::class,
                $bank->bank_id,
                $lama,
                null,
                sprintf('Menghapus bank "%s"%s.', $bank->nama_bank, $rekening ? " — rekening {$rekening}" : '')
            );

            $bank->platformBankAccounts()->delete();
            $bank->delete();

            Notification::fireSelf(Notification::TIPE_SISTEM, 'Bank Dihapus', 'Bank "'.$lama['nama_bank'].'" dihapus.', route('superadmin.data-bank'));
        });

        return back()->with('toast', [
            'message' => 'Bank "'.$lama['nama_bank'].'" berhasil dihapus.',
            'icon' => 'delete',
        ]);
    }

    /** ── E-Wallet / QRIS Account CRUD ─────────────────────────── */

    public function storeAccount(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|in:' . PlatformBankAccount::JENIS_EWALLET . ',' . PlatformBankAccount::JENIS_QRIS,
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:50',
            'nomor_rekening' => 'nullable|string|max:50',
            'nama_pemilik' => 'nullable|string|max:150',
            'deskripsi' => 'nullable|string|max:255',
            'file_gambar' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $payload = [
            'jenis' => $validated['jenis'],
            'nama' => $validated['nama'],
            'kode' => $validated['kode'],
            'nomor_rekening' => $validated['nomor_rekening'] ?? null,
            'nama_pemilik' => $validated['nama_pemilik'] ?? 'RALIVA Fashion',
            'deskripsi' => $validated['deskripsi'] ?? null,
            'urutan' => PlatformBankAccount::where('jenis', $validated['jenis'])->max('urutan') + 1,
            'status' => $validated['status'],
        ];

        if ($request->hasFile('file_gambar')) {
            $payload['file_gambar'] = $request->file('file_gambar')->store('payment_methods', 'public');
        }

        PlatformBankAccount::create($payload);

        $label = $validated['jenis'] === PlatformBankAccount::JENIS_QRIS ? 'QRIS' : 'E-Wallet';

        return back()->with('toast', [
            'message' => "Akun {$label} \"{$validated['nama']}\" berhasil ditambahkan.",
            'icon' => 'task_alt',
        ]);
    }

    public function updateAccount(Request $request, PlatformBankAccount $account)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kode' => 'required|string|max:50',
            'nomor_rekening' => 'nullable|string|max:50',
            'nama_pemilik' => 'nullable|string|max:150',
            'deskripsi' => 'nullable|string|max:255',
            'file_gambar' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $payload = [
            'nama' => $validated['nama'],
            'kode' => $validated['kode'],
            'nomor_rekening' => $validated['nomor_rekening'] ?? null,
            'nama_pemilik' => $validated['nama_pemilik'] ?? 'RALIVA Fashion',
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status' => $validated['status'],
        ];

        if ($request->hasFile('file_gambar')) {
            if ($account->file_gambar) {
                Storage::disk('public')->delete($account->file_gambar);
            }
            $payload['file_gambar'] = $request->file('file_gambar')->store('payment_methods', 'public');
        }

        $account->update($payload);

        $label = $account->jenis === PlatformBankAccount::JENIS_QRIS ? 'QRIS' : 'E-Wallet';

        return back()->with('toast', [
            'message' => "Akun {$label} \"{$validated['nama']}\" berhasil diperbarui.",
            'icon' => 'task_alt',
        ]);
    }

    public function destroyAccount(PlatformBankAccount $account)
    {
        if ($account->jenis === PlatformBankAccount::JENIS_BANK_TRANSFER) {
            return back()->with('toast', [
                'message' => 'Akun bank transfer hanya bisa dihapus lewat hapus bank.',
                'icon' => 'gpp_maybe',
            ]);
        }

        if ($account->file_gambar) {
            Storage::disk('public')->delete($account->file_gambar);
        }

        $nama = $account->nama;
        $account->delete();

        $label = $account->jenis === PlatformBankAccount::JENIS_QRIS ? 'QRIS' : 'E-Wallet';

        return back()->with('toast', [
            'message' => "Akun {$label} \"{$nama}\" berhasil dihapus.",
            'icon' => 'delete',
        ]);
    }
}
