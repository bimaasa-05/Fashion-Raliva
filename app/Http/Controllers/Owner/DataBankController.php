<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\StoreBankAccount;
use App\Support\OwnerContext;
use Illuminate\Http\Request;

class DataBankController extends Controller
{
    public function index()
    {
        $store = OwnerContext::currentStore();
        $bankAccounts = $store ? $store->bankAccounts()->with('bank')->orderByDesc('is_primary')->get() : collect();
        $banks = Bank::where('status', 'aktif')->orderBy('nama_bank')->get();

        return view('Owner.data-bank.index', compact('bankAccounts', 'banks', 'store'));
    }

    public function store(Request $request)
    {
        $store = OwnerContext::currentStore();
        if (! $store) return back()->with('error', 'Anda belum memiliki toko.');

        $data = $request->validate([
            'bank_id' => ['required', 'exists:banks,bank_id'],
            'nomor_rekening' => ['required', 'string', 'max:50'],
            'nama_pemilik' => ['required', 'string', 'max:150'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        if (! empty($data['is_primary'])) {
            StoreBankAccount::where('store_id', $store->store_id)->update(['is_primary' => false]);
        }

        StoreBankAccount::create([
            'store_id' => $store->store_id,
            'bank_id' => $data['bank_id'],
            'nomor_rekening' => $data['nomor_rekening'],
            'nama_pemilik' => $data['nama_pemilik'],
            'is_primary' => ! empty($data['is_primary']),
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Rekening bank berhasil ditambahkan.');
    }

    public function update(Request $request, StoreBankAccount $bankAccount)
    {
        $store = OwnerContext::currentStore();
        if (! $store || (int) $bankAccount->store_id !== (int) $store->store_id) abort(403);

        $data = $request->validate([
            'bank_id' => ['required', 'exists:banks,bank_id'],
            'nomor_rekening' => ['required', 'string', 'max:50'],
            'nama_pemilik' => ['required', 'string', 'max:150'],
            'is_primary' => ['nullable', 'boolean'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        if (! empty($data['is_primary'])) {
            StoreBankAccount::where('store_id', $store->store_id)->where('bank_account_id', '!=', $bankAccount->bank_account_id)->update(['is_primary' => false]);
        }

        $bankAccount->update([
            'bank_id' => $data['bank_id'],
            'nomor_rekening' => $data['nomor_rekening'],
            'nama_pemilik' => $data['nama_pemilik'],
            'is_primary' => ! empty($data['is_primary']),
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Rekening bank diperbarui.');
    }

    public function destroy(StoreBankAccount $bankAccount)
    {
        $store = OwnerContext::currentStore();
        if (! $store || (int) $bankAccount->store_id !== (int) $store->store_id) abort(403);
        $bankAccount->delete();
        return back()->with('success', 'Rekening bank dihapus.');
    }
}
