<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Refund;
use App\Services\RefundCompletionService;
use App\Support\OwnerContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengembalianDanaController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $refunds = Refund::query()
            ->with(['order', 'requester', 'reviewer', 'items'])
            ->where('status', Refund::STATUS_ESKALASI)
            ->orderByDesc('diajukan_pada')
            ->get();

        $disetujui = Refund::query()
            ->with(['order', 'requester', 'reviewer', 'items'])
            ->where('status', Refund::STATUS_DISETUJUI)
            ->orderByDesc('diajukan_pada')
            ->get();

        if ($storeId) {
            $refunds = $refunds->filter(fn ($r) => (int) $r->order?->store_id === (int) $storeId)->values();
            $disetujui = $disetujui->filter(fn ($r) => (int) $r->order?->store_id === (int) $storeId)->values();
        } else {
            $refunds = collect();
            $disetujui = collect();
        }

        return view('Owner.pengembalian-dana.index', [
            'refunds' => $refunds,
            'disetujui' => $disetujui,
            'eskalasiCount' => $refunds->count(),
            'disetujuiCount' => $disetujui->count(),
        ]);
    }

    public function setujui(Request $request, Refund $refund): RedirectResponse
    {
        $this->assertStoreOwnerScope($refund);

        if ($refund->status !== Refund::STATUS_ESKALASI) {
            return back()->with('error', 'Refund sudah diproses.');
        }

        $data = [
            'status' => Refund::STATUS_DISETUJUI,
            'selesai_pada' => now(),
        ];

        if (! $refund->reviewed_by) {
            $data['reviewed_by'] = Auth::id();
        }

        $refund->update($data);

        if ($refund->requested_by) {
            Notification::create([
                'user_id' => $refund->requested_by,
                'aktor_id' => Auth::id(),
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Refund Disetujui Owner',
                'pesan' => sprintf('Pengajuan refund %s disetujui Owner: dana dikembalikan ke saldo Anda.', $refund->kode),
                'url' => route('customer.order-tracking'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Refund Disetujui', sprintf('Refund %s disetujui.', $refund->kode), route('owner.pengembalian-dana'));

        return back()->with('success', 'Refund '.$refund->kode.' disetujui.');
    }

    public function tolak(Request $request, Refund $refund): RedirectResponse
    {
        $this->assertStoreOwnerScope($refund);

        if ($refund->status !== Refund::STATUS_ESKALASI) {
            return back()->with('error', 'Refund sudah diproses.');
        }

        $data = $request->validate([
            'alasan_penolakan' => 'nullable|string|max:1000',
        ]);

        $update = [
            'status' => Refund::STATUS_DITOLAK,
            'alasan_penolakan' => $data['alasan_penolakan'] ?? null,
            'selesai_pada' => now(),
        ];

        if (! $refund->reviewed_by) {
            $update['reviewed_by'] = Auth::id();
        }

        $refund->update($update);

        if ($refund->requested_by) {
            Notification::create([
                'user_id' => $refund->requested_by,
                'aktor_id' => Auth::id(),
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Refund Ditolak Owner',
                'pesan' => sprintf('Pengajuan refund %s ditolak. Alasan: %s', $refund->kode, $data['alasan_penolakan'] ?: '-'),
                'url' => route('customer.order-tracking'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Refund Ditolak', sprintf('Refund %s ditolak.', $refund->kode), route('owner.pengembalian-dana'));

        return back()->with('success', 'Refund '.$refund->kode.' ditolak.');
    }

    public function selesaikan(Request $request, Refund $refund): RedirectResponse
    {
        $this->assertStoreOwnerScope($refund);

        if ($refund->status !== Refund::STATUS_DISETUJUI) {
            return back()->with('error', 'Hanya refund disetujui yang dapat diselesaikan.');
        }

        $data = $request->validate([
            'file_bukti' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'deskripsi_bukti' => ['nullable', 'string', 'max:1000'],
        ], [
            'file_bukti.mimes' => 'Bukti refund harus berupa JPG, PNG, atau PDF.',
            'file_bukti.max' => 'Ukuran bukti refund maksimal 5 MB.',
            'deskripsi_bukti.max' => 'Deskripsi bukti maksimal 1000 karakter.',
        ]);

        $path = $request->hasFile('file_bukti')
            ? $request->file('file_bukti')->store('bukti-refund/'.$refund->refund_id, 'public')
            : null;

        if ($refund->file_bukti && $refund->file_bukti !== $path) {
            Storage::disk('public')->delete($refund->file_bukti);
        }

        try {
            RefundCompletionService::complete($refund, $path, $data['deskripsi_bukti'] ?? null);
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), 'Saldo toko tidak cukup')) {
                return back()->with('error', 'Saldo toko tidak cukup untuk menyelesaikan refund ini.');
            }

            if (
                str_contains($e->getMessage(), 'tidak terhubung ke toko')
                || str_contains($e->getMessage(), 'Wallet toko tidak ditemukan')
                || str_contains($e->getMessage(), 'sudah berubah')
            ) {
                return back()->with('error', 'Refund tidak dapat diselesaikan: '.$e->getMessage());
            }

            throw $e;
        }

        if ($refund->requested_by) {
            Notification::create([
                'user_id' => $refund->requested_by,
                'aktor_id' => Auth::id(),
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Refund Selesai',
                'pesan' => sprintf('Pengajuan refund %s telah diselesaikan oleh Owner.', $refund->kode),
                'url' => route('customer.order-tracking'),
            ]);
        }
        Notification::fireSelf(Notification::TIPE_KOMPLAIN, 'Refund Selesai', sprintf('Refund %s ditandai selesai.', $refund->kode), route('owner.pengembalian-dana'));

        return back()->with('success', 'Refund '.$refund->kode.' ditandai selesai.');
    }

    private function assertStoreOwnerScope(Refund $refund): void
    {
        $storeId = OwnerContext::firstStoreId();

        if (! $storeId) {
            abort(403, 'Toko tidak ditemukan.');
        }

        $order = $refund->order()->select('store_id')->first();

        if (! $order || (int) $order->store_id !== (int) $storeId) {
            abort(403, 'Refund ini bukan untuk toko Anda.');
        }
    }
}
