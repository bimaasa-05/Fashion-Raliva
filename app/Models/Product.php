<?php

namespace App\Models;

use App\Support\ProductCostCalculator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    public const TIPE_REGULAR = 'regular';

    public const TIPE_PREORDER = 'preorder';

    public const TIPE_MADE_TO_ORDER = 'made_to_order';

    public const STATUS_DRAFT = 'draft';

    public const STATUS_PENDING = 'pending';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_ARSIP = 'arsip';

    protected $fillable = [
        'store_id',
        'category_id',
        'nama_produk',
        'deskripsi',
        'harga_dasar',
        'target_produksi',
        'modal_produksi',
        'biaya_tambahan',
        'tipe_produk',
        'status',
        'alasan_penolakan',
    ];

    protected function casts(): array
    {
        return [
            'harga_dasar' => 'float',
            'target_produksi' => 'integer',
            'modal_produksi' => 'float',
            'biaya_tambahan' => 'float',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'product_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id')->orderBy('urutan');
    }

    public function materialRequirements(): HasMany
    {
        return $this->hasMany(ProductMaterialRequirement::class, 'product_id', 'product_id');
    }

    public function operationalCosts(): HasMany
    {
        return $this->hasMany(ProductOperationalCost::class, 'product_id', 'product_id');
    }

    public function updateRequests(): HasMany
    {
        return $this->hasMany(ProductUpdateRequest::class, 'product_id', 'product_id');
    }

    public function productionSummary(): array
    {
        $materials = $this->materialRequirements->map(fn ($requirement) => [
            'jumlah_per_unit' => (float) $requirement->jumlah_per_unit,
            'biaya_per_unit' => (float) $requirement->biaya_per_unit,
        ])->all();

        return ProductCostCalculator::calculate(
            $materials,
            (float) ($this->biaya_tambahan ?? 0),
            (int) ($this->target_produksi ?? 0),
            (float) ($this->harga_dasar ?? 0)
        );
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class, 'product_id', 'product_id');
    }

    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'promotion_products', 'product_id', 'promotion_id')
            ->withPivot('promotion_product_id')
            ->withTimestamps();
    }

    public function adSlot(): HasOne
    {
        return $this->hasOne(AdSlot::class, 'product_id', 'product_id')
            ->where('status', AdSlot::STATUS_AKTIF)
            ->whereDate('tanggal_mulai', '<=', now()->toDateString())
            ->whereDate('tanggal_selesai', '>=', now()->toDateString())
            ->latest('ad_slot_id');
    }
}
