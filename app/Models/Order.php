<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $primaryKey = 'order_id';

    public const STATUS_PENDING_PAYMENT = 'pending_payment';

    public const STATUS_DIBAYAR = 'dibayar';

    public const STATUS_DIPROSES = 'diproses';

    public const STATUS_DIKIRIM = 'dikirim';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const STATUS_REFUND = 'refund';

    public const STATUS_MENUNGGU_PRODUKSI = 'menunggu_produksi';

    public const STATUS_MENUNGGU_QC = 'menunggu_qc';

    public const STATUS_SIAP_KIRIM = 'siap_kirim';

    // Status pesanan berbayar yang menyumbang pendapatan (GMV/omzet/pajak).
    // Dipakai dashboard SA & Peringkat; exclude dibatalkan, refund, pending_payment.
    public const STATUS_PENDAPATAN = [
        self::STATUS_DIBAYAR,
        self::STATUS_MENUNGGU_PRODUKSI,
        self::STATUS_MENUNGGU_QC,
        self::STATUS_DIPROSES,
        self::STATUS_SIAP_KIRIM,
        self::STATUS_DIKIRIM,
        self::STATUS_SELESAI,
    ];

    public const TIPE_PRODUK_TETAP = 'produk_tetap';

    public const TIPE_CUSTOM = 'custom';

    public const TIPE_PESANAN_ONLINE = 'online';

    public const TIPE_PESANAN_OFFLINE = 'offline';

    protected $fillable = [
        'checkout_id',
        'store_id',
        'nomor_order',
        'subtotal',
        'total_diskon',
        'total_pajak',
        'biaya_layanan',
        'total_ongkir',
        'grand_total',
        'status',
        'tipe_pesanan',
        'diambil_pada',
        'tgl_mulai_produksi',
        'tgl_berakhir_produksi',
        'produksi_dimulai_pada',
        'produksi_catatan_tolak',
        'jumlah_berhasil',
        'jumlah_gagal',
        'tanggal_qc',
        'tanggal_packing',
        'tipe_order',
        'status_ketersediaan',
        'catatan_gudang',
        'catatan',
        'dicek_gudang_pada',
    ];

    protected function casts(): array
    {
        return [
            'dicek_gudang_pada' => 'datetime',
            'tgl_mulai_produksi' => 'datetime',
            'tgl_berakhir_produksi' => 'datetime',
            'produksi_dimulai_pada' => 'datetime',
            'tanggal_qc' => 'datetime',
            'tanggal_packing' => 'datetime',
            'diambil_pada' => 'datetime',
        ];
    }

    public function isOffline(): bool
    {
        return $this->tipe_pesanan === self::TIPE_PESANAN_OFFLINE;
    }

    /**
     * Prioritas urutan daftar pesanan: menunggu produksi selalu paling atas,
     * status lain mengikuti urutan pembaruan terbaru.
     */
    public function scopePrioritasStatus($query)
    {
        return $query->orderByRaw('CASE WHEN status = ? THEN 0 ELSE 1 END', [self::STATUS_MENUNGGU_PRODUKSI]);
    }

    /**
     * True bila pembayaran checkout sudah terverifikasi (termasuk tunai offline
     * yang langsung tercatat terverifikasi saat dibuat).
     */
    public function isPaymentVerified(): bool
    {
        return $this->checkout?->payment?->status === Payment::STATUS_TERVERIFIKASI;
    }

    public function isCustom(): bool
    {
        return $this->tipe_order === self::TIPE_CUSTOM;
    }

    public function tipeOrderLabel(): string
    {
        return $this->isCustom() ? 'Custom' : 'Produk Tetap';
    }

    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class, 'checkout_id', 'checkout_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'order_id', 'order_id');
    }

    public function commission(): HasOne
    {
        return $this->hasOne(Commission::class, 'order_id', 'order_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'order_id', 'order_id');
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'order_id', 'order_id');
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'order_id', 'order_id');
    }

    public function bahanList(): HasMany
    {
        return $this->hasMany(ProductionOrderBahan::class, 'order_id', 'order_id');
    }

    public function qualityChecks(): HasMany
    {
        return $this->hasMany(QualityCheck::class, 'order_id', 'order_id');
    }
}
