<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintMessage;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KomplainController extends Controller
{
    /**
     * Daftar komplain milik customer.
     */
    public function index()
    {
        $complaints = Auth::user()->complaints()
            ->with(['order.store', 'messages.sender'])
            ->orderByDesc('dibuat_pada')
            ->paginate(10);

        return view('customer.komplain.index', compact('complaints'));
    }

    /**
     * Form komplain baru, opsional terikat pada satu pesanan.
     */
    public function create(Request $request)
    {
        $orderId = (int) $request->query('order', 0);
        $order = $orderId > 0
            ? Auth::user()->orders()->with('store')->find($orderId)
            : null;

        $eligibleOrders = Auth::user()->orders()
            ->with('store')
            ->whereIn('orders.status', [Order::STATUS_DIKIRIM, Order::STATUS_SELESAI])
            ->orderByDesc('orders.created_at')
            ->get();

        return view('customer.komplain.create', compact('order', 'eligibleOrders'));
    }

    /**
     * Simpan komplain baru + pesan pertama, lalu beri tahu owner toko.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|integer|exists:orders,order_id',
            'kategori' => ['required', Rule::in([
                Complaint::KATEGORI_PRODUK,
                Complaint::KATEGORI_PENGIRIMAN,
                Complaint::KATEGORI_PELAYANAN,
                Complaint::KATEGORI_LAINNYA,
            ])],
            'subjek' => 'required|string|max:150',
            'deskripsi' => 'required|string|min:20|max:2000',
        ], [
            'order_id.required' => 'Pilih pesanan yang komplain.',
            'kategori.required' => 'Pilih kategori komplain.',
            'subjek.required' => 'Judul komplain wajib diisi.',
            'subjek.max' => 'Judul komplain maksimal 150 karakter.',
            'deskripsi.required' => 'Deskripsi komplain wajib diisi.',
            'deskripsi.min' => 'Deskripsi komplain minimal 20 karakter.',
            'deskripsi.max' => 'Deskripsi komplain maksimal 2000 karakter.',
        ]);

        $order = Auth::user()->orders()->with('store')->findOrFail($data['order_id']);

        $complaint = DB::transaction(function () use ($order, $data) {
            $complaint = Complaint::create([
                'user_id' => Auth::id(),
                'order_id' => $order->order_id,
                'store_id' => $order->store_id,
                'kategori' => $data['kategori'],
                'subjek' => $data['subjek'],
                'deskripsi' => $data['deskripsi'],
                'status' => Complaint::STATUS_OPEN,
                'dibuat_pada' => now(),
            ]);

            ComplaintMessage::create([
                'complaint_id' => $complaint->complaint_id,
                'sender_id' => Auth::id(),
                'pesan' => $data['deskripsi'],
                'lampiran' => null,
            ]);

            if ($order->store && $order->store->owner_id) {
                Notification::create([
                    'user_id' => $order->store->owner_id,
                    'tipe' => Notification::TIPE_KOMPLAIN,
                    'judul' => 'Komplain Baru',
                    'pesan' => sprintf('Komplain #%d: %s', $complaint->complaint_id, $data['subjek']),
                ]);
            }

            return $complaint;
        });

        return redirect()->route('customer.komplain', ['open' => $complaint->complaint_id])
            ->with('toast', ['message' => 'Komplain berhasil dikirim. Toko akan segera membalas.', 'icon' => 'task_alt']);
    }

    /**
     * Thread percakapan komplain (milik customer) — diambil AJAX oleh modal chat.
     */
    public function messages(Complaint $komplain)
    {
        abort_unless($komplain->user_id === Auth::id(), 403);

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

    /**
     * Ubah isi pesan milik sendiri (maksimal 15 menit setelah dikirim).
     */
    public function updateMessage(Request $request, Complaint $komplain, ComplaintMessage $message)
    {
        $this->authorizeMessage($komplain, $message);

        if ($message->deleted_at) {
            return response()->json(['message' => 'Pesan sudah dihapus.'], 422);
        }

        if (in_array($komplain->status, [Complaint::STATUS_SELESAI, Complaint::STATUS_DITUTUP], true)) {
            return response()->json(['message' => 'Komplain ini sudah selesai dan tidak dapat diubah.'], 422);
        }

        if ($message->created_at->lt(now()->subMinutes(15))) {
            return response()->json(['message' => 'Pesan hanya dapat diedit dalam 15 menit pertama setelah dikirim.'], 422);
        }

        $data = $request->validate([
            'pesan' => 'required|string|min:3|max:2000',
        ], [
            'pesan.required' => 'Pesan wajib diisi.',
            'pesan.min' => 'Pesan minimal 3 karakter.',
            'pesan.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        $message->update([
            'pesan' => $data['pesan'],
            'edited_at' => now(),
        ]);

        return response()->json($message->toChatArray(Auth::id()));
    }

    /**
     * Hapus pesan — per=all (untuk semua) hanya untuk pesan milik sendiri,
     * per=me (hanya untuk saya) boleh untuk pesan siapa pun di thread.
     */
    public function destroyMessage(Request $request, Complaint $komplain, ComplaintMessage $message)
    {
        $this->authorizeMessage($komplain, $message);

        if (in_array($komplain->status, [Complaint::STATUS_SELESAI, Complaint::STATUS_DITUTUP], true)) {
            return response()->json(['message' => 'Komplain ini sudah selesai dan tidak dapat diubah.'], 422);
        }

        $per = $request->input('per', 'me');

        if ($per === 'me') {
            $deletedBy = $message->deleted_by ?? [];
            if (!in_array(Auth::id(), $deletedBy, true)) {
                $deletedBy[] = Auth::id();
                $message->update(['deleted_by' => $deletedBy]);
            }

            return response()->json([
                'deleted' => true,
                'complaint_message_id' => $message->complaint_message_id,
            ]);
        }

        if ($message->deleted_at) {
            return response()->json(['message' => 'Pesan sudah dihapus.'], 422);
        }

        if ($message->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Hanya pemilik pesan yang dapat menghapus untuk semua orang.'], 403);
        }

        if ($message->created_at->lt(now()->subDays(2))) {
            return response()->json(['message' => 'Pesan hanya dapat dihapus untuk semua orang dalam 2 hari setelah dikirim.'], 422);
        }

        $message->delete();

        return response()->json([
            'deleted' => true,
            'complaint_message_id' => $message->complaint_message_id,
        ]);
    }

    /**
     * Pastikan komplain milik user aktif dan pesan miliknya sendiri.
     */
    private function authorizeMessage(Complaint $komplain, ComplaintMessage $message): void
    {
        abort_unless($komplain->user_id === Auth::id(), 403);
        abort_unless($message->complaint_id === $komplain->complaint_id, 403);
    }

    /**
     * Balas dalam thread komplain (AJAX / modal chat).
     */
    public function storeMessage(Request $request, Complaint $komplain)
    {
        abort_unless($komplain->user_id === Auth::id(), 403);

        if (in_array($komplain->status, [Complaint::STATUS_SELESAI, Complaint::STATUS_DITUTUP], true)) {
            return response()->json(['message' => 'Komplain ini sudah selesai.'], 422);
        }

        $data = $request->validate([
            'pesan' => 'required|string|min:3|max:2000',
        ], [
            'pesan.required' => 'Pesan wajib diisi.',
            'pesan.min' => 'Pesan minimal 3 karakter.',
            'pesan.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        $pesan = DB::transaction(function () use ($komplain, $data) {
            $pesan = ComplaintMessage::create([
                'complaint_id' => $komplain->complaint_id,
                'sender_id' => Auth::id(),
                'pesan' => $data['pesan'],
                'lampiran' => null,
            ]);

            if ($komplain->status !== Complaint::STATUS_OPEN && $komplain->status !== Complaint::STATUS_ESKALASI) {
                $komplain->update(['status' => Complaint::STATUS_OPEN]);
            }

            if ($komplain->store && $komplain->store->owner_id) {
                Notification::create([
                    'user_id' => $komplain->store->owner_id,
                    'tipe' => Notification::TIPE_KOMPLAIN,
                    'judul' => 'Balasan Komplain',
                    'pesan' => sprintf('Customer membalas komplain #%d.', $komplain->complaint_id),
                ]);
            }

            return $pesan;
        });

        return response()->json($pesan->load('sender'), 201);
    }
}