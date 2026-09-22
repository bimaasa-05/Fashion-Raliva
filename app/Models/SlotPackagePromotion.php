<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlotPackagePromotion extends Model
{
    protected $primaryKey = 'slot_promo_id';

    public const TIPE_PERSEN = 'persen';

    public const TIPE_NOMINAL = 'nominal';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    protected $fillable = [
        'creator_id',
        'slot_package_id',
        'kode_promo',
        'nama_promo',
        'tipe_diskon',
        'nilai_diskon',
        'maksimal_diskon',
        'mulai_pada',
        'berakhir_pada',
        'deskripsi',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'nilai_diskon' => 'float',
            'maksimal_diskon' => 'float',
            'mulai_pada' => 'datetime',
            'berakhir_pada' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'user_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(ProductSlotPackage::class, 'slot_package_id', 'slot_package_id');
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_AKTIF)
            ->where('mulai_pada', '<=', now())
            ->where('berakhir_pada', '>=', now());
    }

    /**
     * Promo aktif untuk sebuah paket slot.
     */
    public static function aktifUntuk(int $slotPackageId): ?self
    {
        return self::query()
            ->aktif()
            ->where('slot_package_id', $slotPackageId)
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * Hitung potongan untuk harga paket. Mengembalikan 0 bila tak ada promo
     * atau harga paket tidak tetap (null = paket fleksibel per slot).
     */
    public function potonganUntuk(?float $hargaPaket): float
    {
        if (! $hargaPaket || $hargaPaket <= 0) {
            return 0;
        }

        $potongan = $this->tipe_diskon === self::TIPE_PERSEN
            ? $hargaPaket * ((float) $this->nilai_diskon / 100)
            : (float) $this->nilai_diskon;

        if ($this->tipe_diskon === self::TIPE_PERSEN && $this->maksimal_diskon) {
            $potongan = min($potongan, (float) $this->maksimal_diskon);
        }

        return round(max(0, min($potongan, $hargaPaket)), 0);
    }

    /**
     * Harga akhir setelah promo. null bila promo tidak berlaku.
     */
    public function hargaSetelahPromo(?float $hargaPaket): ?float
    {
        if (! $hargaPaket) {
            return null;
        }

        $potongan = $this->potonganUntuk($hargaPaket);

        return $potongan > 0 ? $hargaPaket - $potongan : null;
    }
}
