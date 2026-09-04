<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Review;
use App\Models\Store;
use App\Models\StoreDocument;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;

class ManajemenTokoController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');

        $ratings = Review::query()
            ->selectRaw('store_id, ROUND(AVG(rating), 1) as rating_rata')
            ->where('status', Review::STATUS_AKTIF)
            ->groupBy('store_id')
            ->pluck('rating_rata', 'store_id');

        $stats = [
            'semua' => Store::count(),
            Store::STATUS_PENDING => Store::where('status', Store::STATUS_PENDING)->count(),
            Store::STATUS_AKTIF => Store::where('status', Store::STATUS_AKTIF)->count(),
            Store::STATUS_NONAKTIF => Store::where('status', Store::STATUS_NONAKTIF)->count(),
            Store::STATUS_DITOLAK => Store::where('status', Store::STATUS_DITOLAK)->count(),
        ];

        $stores = Store::query()
            ->with('owner:user_id,nama_lengkap,email')
            ->withCount(['products', 'orders'])
            ->with('documents')
            ->when($status !== 'semua', fn ($query) => $query->where('status', $status))
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'aktif' THEN 1 WHEN 'nonaktif' THEN 2 ELSE 3 END")
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Store $store) use ($ratings) {
                return (object) [
                    'model' => $store,
                    'initial' => static::initials($store->nama_toko),
                    'owner_nama' => $store->owner->nama_lengkap ?? '-',
                    'joined' => $store->created_at?->translatedFormat('d M Y') ?? '-',
                    'location' => static::shortLocation($store->alamat),
                    'products_count' => $store->products_count,
                    'orders_count' => $store->orders_count,
                    'rating' => $ratings->get($store->store_id),
                    'deskripsi' => $store->deskripsi,
                    'dokumen' => $store->documents,
                ];
            });

        return view('SuperAdmin.manajemen-toko.index', [
            'stores' => $stores,
            'stats' => $stats,
            'activeStatus' => in_array($status, ['semua', Store::STATUS_PENDING, Store::STATUS_AKTIF, Store::STATUS_NONAKTIF, Store::STATUS_DITOLAK], true)
                ? $status
                : 'semua',
        ]);
    }

    public function setujui(Request $request, Store $toko)
    {
        if (! in_array($toko->status, [Store::STATUS_PENDING, Store::STATUS_DITOLAK], true)) {
            return back()->with('toast', [
                'message' => 'Hanya toko berstatus menunggu atau ditolak yang dapat disetujui.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $toko->only(['status', 'alasan_penolakan']);

        $toko->update([
            'status' => Store::STATUS_AKTIF,
            'alasan_penolakan' => null,
        ]);

        ActivityLogger::log(
            'store.approve',
            Store::class,
            $toko->store_id,
            $lama,
            ['status' => Store::STATUS_AKTIF, 'alasan_penolakan' => null],
            sprintf('Menyetujui toko "%s" milik %s.', $toko->nama_toko, $toko->owner->nama_lengkap ?? '-')
        );

        Notification::create([
            'user_id' => $toko->owner_id,
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => 'Toko Disetujui',
            'pesan' => sprintf('Selamat! Toko "%s" telah disetujui dan kini aktif di Raliva.', $toko->nama_toko),
        ]);

        return back()->with('toast', [
            'message' => sprintf('Toko %s disetujui dan kini aktif.', $toko->nama_toko),
            'icon' => 'task_alt',
        ]);
    }

    public function tolak(Request $request, Store $toko)
    {
        if ($toko->status !== Store::STATUS_PENDING) {
            return back()->with('toast', [
                'message' => 'Hanya toko berstatus menunggu yang dapat ditolak.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:10|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 10 karakter.',
        ]);

        $lama = $toko->only(['status', 'alasan_penolakan']);

        $toko->update([
            'status' => Store::STATUS_DITOLAK,
            'alasan_penolakan' => $data['alasan'],
        ]);

        ActivityLogger::log(
            'store.reject',
            Store::class,
            $toko->store_id,
            $lama,
            ['status' => Store::STATUS_DITOLAK, 'alasan_penolakan' => $data['alasan']],
            sprintf('Menolak toko "%s" dengan alasan: %s', $toko->nama_toko, $data['alasan'])
        );

        Notification::create([
            'user_id' => $toko->owner_id,
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => 'Pengajuan Toko Ditolak',
            'pesan' => sprintf('Pengajuan toko "%s" ditolak. Alasan: %s', $toko->nama_toko, $data['alasan']),
        ]);

        return back()->with('toast', [
            'message' => sprintf('Toko %s ditolak. Alasan dikirim ke pemilik toko.', $toko->nama_toko),
            'icon' => 'block',
        ]);
    }

    public function tangguhkan(Request $request, Store $toko)
    {
        if ($toko->status !== Store::STATUS_AKTIF) {
            return back()->with('toast', [
                'message' => 'Hanya toko berstatus aktif yang dapat ditangguhkan.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $toko->only(['status']);

        $toko->update(['status' => Store::STATUS_NONAKTIF]);

        ActivityLogger::log(
            'store.suspend',
            Store::class,
            $toko->store_id,
            $lama,
            ['status' => Store::STATUS_NONAKTIF],
            sprintf('Menangguhkan toko "%s".', $toko->nama_toko)
        );

        Notification::create([
            'user_id' => $toko->owner_id,
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => 'Toko Ditangguhkan',
            'pesan' => sprintf('Toko "%s" ditangguhkan oleh platform. Hubungi dukungan Raliva untuk informasi lebih lanjut.', $toko->nama_toko),
        ]);

        return back()->with('toast', [
            'message' => sprintf('Toko %s ditangguhkan.', $toko->nama_toko),
            'icon' => 'block',
        ]);
    }

    public function aktifkan(Request $request, Store $toko)
    {
        if ($toko->status !== Store::STATUS_NONAKTIF) {
            return back()->with('toast', [
                'message' => 'Hanya toko berstatus ditangguhkan yang dapat diaktifkan kembali.',
                'icon' => 'gpp_maybe',
            ]);
        }

        $lama = $toko->only(['status']);

        $toko->update(['status' => Store::STATUS_AKTIF]);

        ActivityLogger::log(
            'store.reactivate',
            Store::class,
            $toko->store_id,
            $lama,
            ['status' => Store::STATUS_AKTIF],
            sprintf('Mengaktifkan kembali toko "%s".', $toko->nama_toko)
        );

        Notification::create([
            'user_id' => $toko->owner_id,
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => 'Toko Diaktifkan Kembali',
            'pesan' => sprintf('Toko "%s" telah diaktifkan kembali dan dapat beroperasi normal.', $toko->nama_toko),
        ]);

        return back()->with('toast', [
            'message' => sprintf('Toko %s diaktifkan kembali.', $toko->nama_toko),
            'icon' => 'task_alt',
        ]);
    }

    public function verifikasiDokumen(Request $request, Store $toko, StoreDocument $dokumen)
    {
        if ($dokumen->store_id !== $toko->store_id) {
            abort(404);
        }

        if ($dokumen->status === 'terverifikasi') {
            return back()->with('toast', [
                'message' => sprintf('Dokumen %s sudah terverifikasi.', $this->jenisLabel($dokumen->jenis)),
                'icon' => 'info',
            ]);
        }

        $lama = $dokumen->only(['status', 'catatan']);

        $dokumen->update([
            'status' => 'terverifikasi',
            'catatan' => null,
        ]);

        ActivityLogger::log(
            'store.document.approve',
            StoreDocument::class,
            $dokumen->store_document_id,
            $lama,
            ['status' => 'terverifikasi', 'catatan' => null],
            sprintf('Menyetujui dokumen %s toko "%s".', $this->jenisLabel($dokumen->jenis), $toko->nama_toko)
        );

        Notification::create([
            'user_id' => $toko->owner_id,
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => 'Dokumen Disetujui',
            'pesan' => sprintf('Dokumen %s toko "%s" disetujui oleh Super Admin.', $this->jenisLabel($dokumen->jenis), $toko->nama_toko),
        ]);

        return back()->with('toast', [
            'message' => sprintf('Dokumen %s disetujui.', $this->jenisLabel($dokumen->jenis)),
            'icon' => 'task_alt',
        ]);
    }

    public function tolakDokumen(Request $request, Store $toko, StoreDocument $dokumen)
    {
        if ($dokumen->store_id !== $toko->store_id) {
            abort(404);
        }

        if ($dokumen->status === 'terverifikasi') {
            return back()->with('toast', [
                'message' => sprintf('Dokumen %s sudah terverifikasi, tidak dapat ditolak.', $this->jenisLabel($dokumen->jenis)),
                'icon' => 'gpp_maybe',
            ]);
        }

        $data = $request->validate([
            'alasan' => 'required|string|min:3|max:1000',
        ], [
            'alasan.required' => 'Alasan penolakan wajib diisi.',
            'alasan.min' => 'Alasan penolakan minimal 3 karakter.',
        ]);

        $lama = $dokumen->only(['status', 'catatan']);

        $dokumen->update([
            'status' => 'ditolak',
            'catatan' => $data['alasan'],
        ]);

        ActivityLogger::log(
            'store.document.reject',
            StoreDocument::class,
            $dokumen->store_document_id,
            $lama,
            ['status' => 'ditolak', 'catatan' => $data['alasan']],
            sprintf('Menolak dokumen %s toko "%s" dengan alasan: %s', $this->jenisLabel($dokumen->jenis), $toko->nama_toko, $data['alasan'])
        );

        Notification::create([
            'user_id' => $toko->owner_id,
            'tipe' => Notification::TIPE_SISTEM,
            'judul' => 'Dokumen Ditolak',
            'pesan' => sprintf('Dokumen %s toko "%s" ditolak. Alasan: %s', $this->jenisLabel($dokumen->jenis), $toko->nama_toko, $data['alasan']),
        ]);

        return back()->with('toast', [
            'message' => sprintf('Dokumen %s ditolak.', $this->jenisLabel($dokumen->jenis)),
            'icon' => 'block',
        ]);
    }

    private static function jenisLabel(string $jenis): string
    {
        return match ($jenis) {
            'ktp' => 'KTP / Identitas Owner',
            'npwp' => 'NPWP Toko',
            'foto_depan' => 'Foto Depan Toko',
            'siu' => 'Surat Izin Usaha (NIB)',
            default => ucfirst($jenis),
        };
    }

    private static function initials(string $nama): string
    {
        $words = preg_split('/\s+/', trim($nama)) ?: [];

        return strtoupper(substr(collect($words)->map(fn ($w) => mb_substr($w, 0, 1))->implode(''), 0, 2));
    }

    private static function shortLocation(string $alamat): string
    {
        $parts = array_map('trim', explode(',', $alamat));

        return end($parts) ?: $alamat;
    }
}
