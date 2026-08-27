<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ProductSlotPackage;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class PaketSlotProdukController extends Controller
{
    public function index()
    {
        $packages = ProductSlotPackage::query()
            ->withCount(['subscriptions' => function ($q) {
                $q->where('status', 'aktif');
            }])
            ->orderBy('harga')
            ->get();

        return view('SuperAdmin.paket-slot-produk.index', [
            'packages' => $packages,
            'stats' => [
                'total' => $packages->count(),
                'aktif' => $packages->where('status', ProductSlotPackage::STATUS_AKTIF)->count(),
                'nonaktif' => $packages->where('status', ProductSlotPackage::STATUS_NONAKTIF)->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatePaket($request);

        if (ProductSlotPackage::whereRaw('LOWER(nama_paket) = ?', [mb_strtolower($data['nama_paket'])])->exists()) {
            return back()->with('toast', [
                'message' => 'Nama paket "'.$data['nama_paket'].'" sudah digunakan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $paket = ProductSlotPackage::create($data);

        ActivityLogger::log(
            'slot_package.create',
            ProductSlotPackage::class,
            $paket->slot_package_id,
            null,
            $paket->only(['nama_paket', 'harga', 'jumlah_slot', 'durasi_hari', 'status']),
            sprintf('Menambahkan paket slot "%s" — %d slot, Rp %s/bln.', $paket->nama_paket, $paket->jumlah_slot, number_format($paket->harga, 0, ',', '.'))
        );

        return back()->with('toast', [
            'message' => 'Paket slot "'.$paket->nama_paket.'" berhasil ditambahkan.',
            'icon' => 'task_alt',
        ]);
    }

    public function update(Request $request, ProductSlotPackage $paket)
    {
        $data = $this->validatePaket($request);

        if (ProductSlotPackage::whereRaw('LOWER(nama_paket) = ?', [mb_strtolower($data['nama_paket'])])->where('slot_package_id', '!=', $paket->slot_package_id)->exists()) {
            return back()->with('toast', [
                'message' => 'Nama paket "'.$data['nama_paket'].'" sudah digunakan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $paket->only(['nama_paket', 'harga', 'jumlah_slot', 'durasi_hari', 'status']);

        $paket->update($data);

        ActivityLogger::log(
            'slot_package.update',
            ProductSlotPackage::class,
            $paket->slot_package_id,
            $lama,
            $paket->only(['nama_paket', 'harga', 'jumlah_slot', 'durasi_hari', 'status']),
            sprintf('Mengubah paket slot "%s" → "%s".', $lama['nama_paket'], $paket->nama_paket)
        );

        return back()->with('toast', [
            'message' => 'Perubahan paket "'.$paket->nama_paket.'" berhasil disimpan.',
            'icon' => 'task_alt',
        ]);
    }

    public function hapus(Request $request, ProductSlotPackage $paket)
    {
        $subscriberCount = $paket->subscriptions()->where('status', 'aktif')->count();

        if ($subscriberCount > 0) {
            return back()->with('toast', [
                'message' => 'Hapus dibatalkan — paket "'.$paket->nama_paket.'" masih memiliki '.$subscriberCount.' subscriber aktif.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $paket->only(['nama_paket', 'harga', 'jumlah_slot', 'durasi_hari', 'status']);

        ActivityLogger::log(
            'slot_package.delete',
            ProductSlotPackage::class,
            $paket->slot_package_id,
            $lama,
            null,
            sprintf('Menghapus paket slot "%s".', $paket->nama_paket)
        );

        $paket->delete();

        return back()->with('toast', [
            'message' => 'Paket slot "'.$lama['nama_paket'].'" berhasil dihapus.',
            'icon' => 'delete',
        ]);
    }

    private function validatePaket(Request $request): array
    {
        return $request->validate([
            'nama_paket' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0|max:999999999',
            'jumlah_slot' => 'required|integer|min:1|max:99999',
            'durasi_hari' => 'required|integer|min:1|max:3650',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_paket.required' => 'Nama paket wajib diisi.',
            'nama_paket.max' => 'Nama paket maksimal 100 karakter.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga minimal 0.',
            'jumlah_slot.required' => 'Jumlah slot wajib diisi.',
            'jumlah_slot.min' => 'Jumlah slot minimal 1.',
            'durasi_hari.required' => 'Durasi wajib diisi.',
            'durasi_hari.min' => 'Durasi minimal 1 hari.',
            'status.required' => 'Status wajib dipilih.',
        ]);
    }
}
