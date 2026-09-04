<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Store extends Model
{
    protected $primaryKey = 'store_id';

    public const STATUS_PENDING = 'pending';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = [
        'owner_id',
        'nama_toko',
        'logo',
        'deskripsi',
        'alamat',
        'nomor_telepon',
        'status',
        'alasan_penolakan',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'user_id');
    }

    public function storeStaff(): HasMany
    {
        return $this->hasMany(StoreStaff::class, 'store_id', 'store_id');
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'store_staff', 'store_id', 'user_id')
            ->withPivot('store_staff_id', 'tanggal_penugasan', 'status')
            ->withTimestamps();
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class, 'store_id', 'store_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'store_id', 'store_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'store_id', 'store_id');
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(StoreBankAccount::class, 'store_id', 'store_id');
    }

    public function slotSubscriptions(): HasMany
    {
        return $this->hasMany(StoreSlotSubscription::class, 'store_id', 'store_id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'store_id', 'store_id');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'store_id', 'store_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class, 'store_id', 'store_id');
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'store_id', 'store_id');
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'store_id', 'store_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'store_id', 'store_id');
    }

    public function productionOrders(): HasMany
    {
        return $this->hasMany(ProductionOrder::class, 'store_id', 'store_id');
    }

    protected function casts(): array
    {
        return [];
    }
}
