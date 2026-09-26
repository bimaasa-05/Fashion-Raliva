<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Tampilkan daftar alamat pengguna.
     */
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('is_default', 'desc')->orderBy('updated_at', 'desc')->get();
        $backUrl = $this->backUrl(request());

        return view('customer.address.index', compact('addresses', 'backUrl'));
    }

    /**
     * Tampilkan formulir tambah alamat baru.
     */
    public function create()
    {
        $cities = \App\Models\City::orderBy('city_id')->get()->groupBy('pulau')
            ->map(fn ($g) => $g->pluck('nama_kota')->values()->all())->all();
        $backUrl = $this->backUrl(request());

        return view('customer.address.create', compact('cities', 'backUrl'));
    }

    /**
     * Simpan alamat baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'nama_penerima' => 'required|string|max:150',
            'nomor_telepon' => 'required|string|max:30',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100|exists:cities,nama_kota',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:20',
            'negara' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
        ], [
            'label.required' => 'Label wajib diisi.',
            'label.max' => 'Label maksimal 100 karakter.',
            'nama_penerima.required' => 'Nama penerima wajib diisi.',
            'nama_penerima.max' => 'Nama penerima maksimal 150 karakter.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'nomor_telepon.max' => 'Nomor telepon maksimal 30 karakter.',
            'alamat.required' => 'Alamat wajib diisi.',
            'kota.required' => 'Kota wajib diisi.',
            'kota.max' => 'Kota maksimal 100 karakter.',
            'kota.exists' => 'Pilih kota dari daftar yang tersedia.',
            'provinsi.max' => 'Provinsi maksimal 100 karakter.',
            'kode_pos.max' => 'Kode pos maksimal 20 karakter.',
            'negara.max' => 'Negara maksimal 100 karakter.',
        ]);

        if (! empty($validated['is_default'])) {
            // Batalkan default sebelumnya
            Auth::user()->addresses()->where('is_default', true)->update(['is_default' => false]);
        }

        $validated['user_id'] = Auth::id();

        Address::create($validated);

        return $this->redirectBack($request, 'customer.address.index')->with('toast', [
            'message' => 'Alamat berhasil ditambahkan.',
            'icon' => 'task_alt',
        ]);
    }

    /**
     * Tampilkan formulir edit alamat.
     */
    public function edit(Address $address)
    {
        $this->authorizeAddress($address);

        $cities = \App\Models\City::orderBy('city_id')->get()->groupBy('pulau')
            ->map(fn ($g) => $g->pluck('nama_kota')->values()->all())->all();
        $backUrl = $this->backUrl(request());

        return view('customer.address.edit', compact('address', 'cities', 'backUrl'));
    }

    /**
     * Perbarui alamat.
     */
    public function update(Request $request, Address $address)
    {
        $this->authorizeAddress($address);

        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'nama_penerima' => 'required|string|max:150',
            'nomor_telepon' => 'required|string|max:30',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100|exists:cities,nama_kota',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:20',
            'negara' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
        ], [
            'label.required' => 'Label wajib diisi.',
            'label.max' => 'Label maksimal 100 karakter.',
            'nama_penerima.required' => 'Nama penerima wajib diisi.',
            'nama_penerima.max' => 'Nama penerima maksimal 150 karakter.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'nomor_telepon.max' => 'Nomor telepon maksimal 30 karakter.',
            'alamat.required' => 'Alamat wajib diisi.',
            'kota.required' => 'Kota wajib diisi.',
            'kota.max' => 'Kota maksimal 100 karakter.',
            'kota.exists' => 'Pilih kota dari daftar yang tersedia.',
            'provinsi.max' => 'Provinsi maksimal 100 karakter.',
            'kode_pos.max' => 'Kode pos maksimal 20 karakter.',
            'negara.max' => 'Negara maksimal 100 karakter.',
        ]);

        if (! empty($validated['is_default'])) {
            Auth::user()->addresses()->where('is_default', true)->where('address_id', '!=', $address->address_id)->update(['is_default' => false]);
        }

        $address->update($validated);

        return $this->redirectBack($request, 'customer.address.index')->with('toast', [
            'message' => 'Alamat berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    /**
     * Hapus alamat.
     */
    public function destroy(Address $address)
    {
        $this->authorizeAddress($address);

        $address->delete();

        return back()->with('toast', [
            'message' => 'Alamat berhasil dihapus.',
            'icon' => 'delete',
        ]);
    }

    /**
     * Set alamat sebagai default.
     */
    public function setDefault(Address $address)
    {
        $this->authorizeAddress($address);

        Auth::user()->addresses()->where('is_default', true)->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('toast', [
            'message' => 'Alamat default berhasil diperbarui.',
            'icon' => 'task_alt',
        ]);
    }

    /**
     * Pastikan alamat milik pengguna yang sedang login.
     */
    protected function authorizeAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Alamat tidak ditemukan.');
        }
    }

    /**
     * URL kembali: ke checkout bila datang dari sana, selain itu null (fallback view ke akun).
     */
    protected function backUrl(Request $request): ?string
    {
        $back = $request->input('back', $request->query('back'));
        if ($back !== 'checkout') {
            return null;
        }
        $buy = (int) $request->input('buy', $request->query('buy', 0));
        $params = $buy > 0 ? ['buy' => $buy] : [];

        return route('customer.checkout', $params);
    }

    protected function redirectBack(Request $request, string $fallback)
    {
        return redirect()->to($this->backUrl($request) ?? route($fallback));
    }
}
