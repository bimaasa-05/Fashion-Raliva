<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\PlatformBankAccount;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class DataBankController extends Controller
{
    public function index()
    {
        $banks = Bank::query()
            ->with('platformBankAccounts')
            ->orderBy('nama_bank')
            ->get();

        $totalRekening = PlatformBankAccount::count();

        return view('SuperAdmin.data-bank.index', [
            'banks' => $banks,
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

        $bank = Bank::create([
            'nama_bank' => $data['nama_bank'],
            'kode_bank' => $data['kode_bank'],
            'status' => $data['status'],
        ]);

        PlatformBankAccount::create([
            'bank_id' => $bank->bank_id,
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

        $bank->update([
            'nama_bank' => $data['nama_bank'],
            'kode_bank' => $data['kode_bank'],
            'status' => $data['status'],
        ]);

        if ($rekeningLama) {
            $rekeningLama->update([
                'nomor_rekening' => $data['nomor_rekening'],
                'nama_pemilik' => $data['nama_pemilik'],
                'status' => $data['status'],
            ]);
        } else {
            PlatformBankAccount::create([
                'bank_id' => $bank->bank_id,
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

        return back()->with('toast', [
            'message' => 'Bank "'.$lama['nama_bank'].'" berhasil dihapus.',
            'icon' => 'delete',
        ]);
    }
}
