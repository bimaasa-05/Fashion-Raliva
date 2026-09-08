<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Models\Notification;
use App\Support\OwnerContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomplainController extends Controller
{
    public function index(Request $request)
    {
        $storeId = OwnerContext::firstStoreId();

        $complaints = Complaint::with('user')
            ->where('store_id', $storeId)
            ->orderByDesc('dibuat_pada')
            ->paginate(10)
            ->withQueryString();

        $all = Complaint::where('store_id', $storeId)->get();
        $terbuka = $all->whereIn('status', [Complaint::STATUS_OPEN, Complaint::STATUS_DIPROSES])->count();
        $menunggu = $all->where('status', Complaint::STATUS_OPEN)->count();
        $selesai = $all->where('status', Complaint::STATUS_SELESAI)->count();
        $selesaiBulanIni = $all->where('status', Complaint::STATUS_SELESAI)
            ->filter(fn($c) => $c->dibuat_pada && $c->dibuat_pada->month === now()->month)
            ->count();
        $resolution = $all->count() > 0 ? round($selesai / $all->count() * 100) : 0;

        return view('Owner.komplain.index', compact(
            'complaints', 'terbuka', 'menunggu', 'selesaiBulanIni', 'resolution'
        ));
    }

    public function messages(Complaint $komplain)
    {
        abort_unless($this->belongsToStore($komplain), 404);

        $komplain->load([
            'user',
            'order',
            'messages' => fn ($q) => $q->withTrashed()->with('sender')->orderBy('created_at'),
        ]);

        return view('Owner.komplain.messages', compact('komplain'));
    }

    public function balas(Request $request, Complaint $komplain)
    {
        abort_unless($this->belongsToStore($komplain), 404);

        $request->validate([
            'pesan' => 'required|string|min:3|max:2000',
        ], [
            'pesan.required' => 'Pesan wajib diisi.',
            'pesan.min' => 'Pesan minimal 3 karakter.',
            'pesan.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        ComplaintMessage::create([
            'complaint_id' => $komplain->complaint_id,
            'sender_id' => Auth::id(),
            'pesan' => $request->input('pesan'),
            'lampiran' => null,
        ]);

        if ($komplain->status === Complaint::STATUS_OPEN) {
            $komplain->update(['status' => Complaint::STATUS_DIPROSES]);
        }

        if ($komplain->user_id) {
            Notification::create([
                'user_id' => $komplain->user_id,
                'tipe' => Notification::TIPE_KOMPLAIN,
                'judul' => 'Balasan Komplain',
                'pesan' => sprintf('Toko membalas komplain #%d.', $komplain->complaint_id),
            ]);
        }

        return back()->with('success', 'Balasan terkirim ke customer.');
    }

    protected function belongsToStore(Complaint $complaint): bool
    {
        return (int) $complaint->store_id === (int) OwnerContext::firstStoreId();
    }
}