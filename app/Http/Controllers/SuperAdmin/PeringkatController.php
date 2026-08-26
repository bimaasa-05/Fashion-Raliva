<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PeringkatController extends Controller
{
    private const VALID_ORDER_STATUSES = [
        Order::STATUS_DIBAYAR,
        Order::STATUS_DIPROSES,
        Order::STATUS_DIKIRIM,
        Order::STATUS_SELESAI,
    ];

    public function index(Request $request)
    {
        $periode = $request->query('periode', 'all');
        $since = $this->resolveSince($periode);

        return view('SuperAdmin.peringkat.index', [
            'periode' => $periode,
            'topToko' => $this->topToko($since),
            'topKategori' => $this->topKategori($since),
            'topPelanggan' => $this->topPelanggan($since),
        ]);
    }

    public static function resolveSince(string $periode): ?Carbon
    {
        return match ($periode) {
            '7' => now()->subDays(7)->startOfDay(),
            '30' => now()->subDays(30)->startOfDay(),
            default => null,
        };
    }

    private function topToko(?Carbon $since): array
    {
        $rows = Order::query()
            ->selectRaw('stores.store_id, stores.nama_toko, stores.alamat')
            ->selectRaw('SUM(orders.grand_total) as total_omzet')
            ->selectRaw('COUNT(*) as jumlah_pesanan')
            ->join('stores', 'stores.store_id', '=', 'orders.store_id')
            ->whereIn('orders.status', self::VALID_ORDER_STATUSES)
            ->when($since, fn (Builder $query) => $query->where('orders.created_at', '>=', $since))
            ->groupBy('stores.store_id', 'stores.nama_toko', 'stores.alamat')
            ->orderByDesc('total_omzet')
            ->limit(10)
            ->get();

        $ratings = Review::query()
            ->selectRaw('store_id, ROUND(AVG(rating), 1) as rating_rata, COUNT(*) as jumlah_ulasan')
            ->where('status', Review::STATUS_AKTIF)
            ->groupBy('store_id')
            ->pluck('rating_rata', 'store_id');

        return $this->decorate($rows, fn (object $row) => [
            'id' => $row->store_id,
            'nama' => $row->nama_toko,
            'meta' => sprintf('%s pesanan • Rating %s', number_format((float) $row->jumlah_pesanan, 0, ',', '.'), $ratings->get($row->store_id, '-')),
            'sub_meta' => $row->alamat,
            'nilai' => (float) $row->total_omzet,
        ]);
    }

    private function topKategori(?Carbon $since): array
    {
        $rows = DB::table('order_items')
            ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->join('product_variants', 'product_variants.product_variant_id', '=', 'order_items.product_variant_id')
            ->join('products', 'products.product_id', '=', 'product_variants.product_id')
            ->join('categories', 'categories.category_id', '=', 'products.category_id')
            ->whereIn('orders.status', self::VALID_ORDER_STATUSES)
            ->when($since, fn ($query) => $query->where('orders.created_at', '>=', $since))
            ->groupBy('categories.category_id', 'categories.nama_kategori')
            ->selectRaw('categories.category_id, categories.nama_kategori')
            ->selectRaw('SUM(order_items.quantity) as total_terjual')
            ->selectRaw('SUM(order_items.total) as total_omzet')
            ->selectRaw('COUNT(DISTINCT products.store_id) as jumlah_toko')
            ->orderByDesc('total_omzet')
            ->limit(10)
            ->get();

        return $this->decorate($rows, fn (object $row) => [
            'id' => $row->category_id,
            'nama' => $row->nama_kategori,
            'meta' => sprintf('%s terjual • %s toko aktif', number_format((float) $row->total_terjual, 0, ',', '.'), $row->jumlah_toko),
            'sub_meta' => null,
            'nilai' => (float) $row->total_omzet,
        ]);
    }

    private function topPelanggan(?Carbon $since): array
    {
        $rows = DB::table('orders')
            ->join('checkouts', 'checkouts.checkout_id', '=', 'orders.checkout_id')
            ->join('users', 'users.user_id', '=', 'checkouts.user_id')
            ->whereIn('orders.status', self::VALID_ORDER_STATUSES)
            ->when($since, fn ($query) => $query->where('orders.created_at', '>=', $since))
            ->groupBy('users.user_id', 'users.nama_lengkap')
            ->selectRaw('users.user_id, users.nama_lengkap')
            ->selectRaw('SUM(orders.grand_total) as total_belanja')
            ->selectRaw('COUNT(*) as jumlah_pesanan')
            ->selectRaw('MIN(orders.created_at) as pesanan_pertama')
            ->orderByDesc('total_belanja')
            ->limit(10)
            ->get();

        return $this->decorate($rows, fn (object $row) => [
            'id' => $row->user_id,
            'nama' => $row->nama_lengkap,
            'meta' => sprintf('%s pesanan • Loyal sejak %s', number_format((float) $row->jumlah_pesanan, 0, ',', '.'), Carbon::parse($row->pesanan_pertama)->translatedFormat('M Y')),
            'sub_meta' => null,
            'nilai' => (float) $row->total_belanja,
        ]);
    }

    private function decorate($rows, callable $mapper): array
    {
        $items = collect($rows)->map($mapper)->values();
        $max = (float) $items->max('nilai');

        return $items
            ->map(function (array $item, int $index) use ($max) {
                $item['peringkat'] = $index + 1;
                $item['persentase'] = $max > 0 ? max(4, (int) round($item['nilai'] / $max * 100)) : 0;
                $item['display'] = 'Rp '.number_format($item['nilai'], 0, ',', '.');

                return $item;
            })
            ->all();
    }
}
