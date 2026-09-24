<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Store;
use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdSlot extends Model
{
    protected $primaryKey = 'ad_slot_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    public const STATUS_DITUNDA = 'ditunda';

    public const STATUS_TERJADWAL = 'terjadwal';

    public const PAYMENT_MENUNGGU = 'menunggu_verifikasi';

    public const PAYMENT_TERVERIFIKASI = 'terverifikasi';

    public const PAYMENT_DITOLAK = 'ditolak';

    protected $fillable = [
        'product_id',
        'store_id',
        'nominal_bid',
        'payment_status',
        'metode_pembayaran',
        'file_bukti',
        'platform_bank_account_id',
        'paid_at',
        'handled_by',
        'alasan_penolakan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'nominal_bid' => 'decimal:2',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(PlatformBankAccount::class, 'platform_bank_account_id', 'platform_bank_account_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by', 'user_id');
    }

    /**
     * Aktifkan iklan terjadwal saat tanggal mulai tiba dan nonaktifkan iklan yang melewati tanggal selesai.
     */
    public static function autoProcess(): int
    {
        $today = now()->toDateString();
        $changed = 0;

        $mulaiTayang = self::query()
            ->where('status', self::STATUS_TERJADWAL)
            ->whereNotNull('tanggal_mulai')
            ->whereNotNull('tanggal_selesai')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->get();

        foreach ($mulaiTayang as $slot) {
            $slot->loadMissing(['store', 'product']);

            $slot->update(['status' => self::STATUS_AKTIF]);
            $changed++;

            ActivityLogger::log(
                'ad_slot.auto_activate',
                self::class,
                $slot->ad_slot_id,
                ['status' => self::STATUS_TERJADWAL],
                ['status' => self::STATUS_AKTIF],
                'Iklan peringkat otomatis aktif (tanggal mulai tiba).'
            );

            $ownerId = $slot->store?->owner_id;
            if ($ownerId) {
                Notification::create([
                    'user_id' => $ownerId,
                    'aktor_id' => null,
                    'tipe' => Notification::TIPE_PROMO,
                    'judul' => 'Iklan Mulai Tayang',
                    'pesan' => sprintf('Iklan "%s" periode %s s/d %s mulai tayang.', $slot->product->nama_produk ?? '-', $slot->tanggal_mulai?->translatedFormat('d M Y') ?? '-', $slot->tanggal_selesai?->translatedFormat('d M Y') ?? '-'),
                    'url' => route('owner.peringkat-iklan'),
                ]);
            }
        }

        $melewatiSelesai = self::query()
            ->whereIn('status', [self::STATUS_AKTIF, self::STATUS_TERJADWAL])
            ->whereNotNull('tanggal_selesai')
            ->whereDate('tanggal_selesai', '<', $today)
            ->get();

        foreach ($melewatiSelesai as $slot) {
            $lama = $slot->status;
            $slot->update(['status' => self::STATUS_NONAKTIF]);
            $changed++;

            ActivityLogger::log(
                'ad_slot.auto_expire',
                self::class,
                $slot->ad_slot_id,
                ['status' => $lama],
                ['status' => self::STATUS_NONAKTIF],
                'Iklan peringkat otomatis nonaktif (periode berakhir).'
            );
        }

        return $changed;
    }

    /**
     * Produk dengan iklan aktif (periode berjalan), diurutkan bid tertinggi.
     */
    public static function activeProducts(int $limit = 10, ?string $term = null)
    {
        $today = now()->toDateString();

        return static::query()
            ->where('status', self::STATUS_AKTIF)
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->with(['product' => function ($q) use ($term) {
                $q->where('status', Product::STATUS_AKTIF)
                    ->whereHas('store', fn ($s) => $s->where('status', Store::STATUS_AKTIF))
                    ->with([
                        'store:store_id,nama_toko,logo',
                        'images' => fn ($img) => $img->orderBy('urutan'),
                        'variants' => fn ($v) => $v->where('status', 'aktif'),
                    ]);

                if ($term !== null && trim($term) !== '') {
                    $like = '%'.trim($term).'%';
                    $q->where(fn ($w) => $w
                        ->where('nama_produk', 'like', $like)
                        ->orWhereHas('store', fn ($s) => $s->where('nama_toko', 'like', $like))
                        ->orWhereHas('category', fn ($c) => $c->where('nama_kategori', 'like', $like))
                        ->orWhereHas('category.parent', fn ($c) => $c->where('nama_kategori', 'like', $like)));
                }
            }])
            ->orderByDesc('nominal_bid')
            ->limit(max(1, $limit))
            ->get()
            ->pluck('product')
            ->filter()
            ->values();
    }
}
