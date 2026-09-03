<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Support\ActivityLogger;

class UlasanProdukTokoController extends Controller
{
    public function index()
    {
        $reviews = Review::with([
            'user:user_id,nama_lengkap,email',
            'store:store_id,nama_toko',
            'product:product_id,nama_produk',
        ])->orderByDesc('created_at')->get();

        $totalToko = $reviews->pluck('store_id')->unique()->count();

        $reviewsJson = $reviews->map(fn ($r) => [
            'id' => $r->review_id,
            'reviewer' => $r->user->nama_lengkap ?? '-',
            'email' => $r->user->email ?? '-',
            'initial' => substr($r->user->nama_lengkap ?? '?', 0, 1),
            'store' => $r->store->nama_toko ?? '-',
            'product' => $r->product->nama_produk ?? '-',
            'rating' => $r->rating,
            'ulasan' => $r->ulasan ?? 'Tidak ada ulasan teks',
            'status' => $r->status,
            'date' => $r->created_at->translatedFormat('d M Y, H:i'),
        ])->values()->all();

        return view('SuperAdmin.ulasan-produk-toko.index', [
            'reviews' => $reviews,
            'reviewsJson' => $reviewsJson,
            'stats' => [
                'total' => $reviews->count(),
                'aktif' => $reviews->where('status', Review::STATUS_AKTIF)->count(),
                'nonaktif' => $reviews->where('status', Review::STATUS_NONAKTIF)->count(),
                'rata_rating' => round($reviews->avg('rating'), 1),
                'total_toko' => $totalToko,
            ],
        ]);
    }

    public function nonaktifkan(Review $review)
    {
        $old = $review->toArray();

        $review->update(['status' => Review::STATUS_NONAKTIF]);

        ActivityLogger::log(
            'review.moderate',
            Review::class,
            $review->review_id,
            $old,
            $review->toArray(),
            sprintf('Menonaktifkan ulasan produk "%s" dari pengguna "%s".', $review->product->nama_produk ?? '-', $review->user->nama_lengkap ?? '-')
        );

        return back()->with('toast', [
            'message' => 'Ulasan berhasil dinonaktifkan.',
            'icon' => 'task_alt',
        ]);
    }

    public function aktifkan(Review $review)
    {
        $old = $review->toArray();

        $review->update(['status' => Review::STATUS_AKTIF]);

        ActivityLogger::log(
            'review.moderate',
            Review::class,
            $review->review_id,
            $old,
            $review->toArray(),
            sprintf('Mengaktifkan kembali ulasan produk "%s" dari pengguna "%s".', $review->product->nama_produk ?? '-', $review->user->nama_lengkap ?? '-')
        );

        return back()->with('toast', [
            'message' => 'Ulasan berhasil diaktifkan kembali.',
            'icon' => 'task_alt',
        ]);
    }
}
