<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomplainController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::query()
            ->with(['user:user_id,nama_lengkap', 'store:store_id,nama_toko,owner_id'])
            ->orderByRaw("CASE status WHEN 'open' THEN 0 WHEN 'diproses' THEN 1 WHEN 'escalated' THEN 2 ELSE 3 END")
            ->orderByDesc('dibuat_pada');

        $stats = [
            'semua' => Complaint::count(),
            'open' => Complaint::where('status', Complaint::STATUS_OPEN)->count(),
            'diproses' => Complaint::where('status', Complaint::STATUS_DIPROSES)->count(),
            Complaint::STATUS_ESKALASI => Complaint::where('status', Complaint::STATUS_ESKALASI)->count(),
            'selesai' => Complaint::where('status', Complaint::STATUS_SELESAI)->count(),
            'ditutup' => Complaint::where('status', Complaint::STATUS_DITUTUP)->count(),
        ];

        $complaints = $query->paginate(20)->withQueryString();

        return view('SuperAdmin.komplain.index', [
            'complaints' => $complaints,
            'stats' => $stats,
        ]);
    }

    public function messages(Complaint $komplain)
    {
        $messages = $komplain->messages()
            ->withTrashed()
            ->with('sender.role')
            ->orderBy('created_at')
            ->get()
            ->reject(fn ($message) => $message->deletedFor(Auth::id()))
            ->map(fn ($message) => $message->toChatArray(Auth::id()))
            ->values();

        return response()->json($messages);
    }
}