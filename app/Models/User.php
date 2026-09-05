<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Order;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_NONAKTIF = 'nonaktif';

    public const STATUS_SUSPEND = 'suspend';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'role_id',
        'nomor_telepon',
        'foto_profil',
        'status',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * URL lengkap foto profil. Menangani dua lokasi penyimpanan:
     * - baru: public/profil/... (path disimpan sebagai "profil/namafile.jpg")
     * - lama: storage/app/public/profil/... (juga "profil/namafile.jpg")
     */
    public function getFotoProfilUrlAttribute(): ?string
    {
        if (empty($this->foto_profil)) {
            return null;
        }

        if (file_exists(public_path($this->foto_profil))) {
            return asset($this->foto_profil);
        }

        $legacy = 'profil/' . basename($this->foto_profil);
        if (file_exists(storage_path('app/public/' . $legacy))) {
            return asset('storage/' . $legacy);
        }

        return null;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * Cek apakah user memiliki permission tertentu berdasarkan tabel
     * role_permissions. Super Admin dianggap memiliki seluruh permission.
     *
     * @param string $kode Kode permission, mis. 'warehouse.stock_in'
     */
    public function hasPermission(string $kode): bool
    {
        $role = $this->role;

        if (! $role) {
            return false;
        }

        // Super Admin memiliki seluruh permission platform.
        if ($role->nama_role === Role::SUPER_ADMIN) {
            return true;
        }

        return $role->permissions()
            ->where('kode_permission', $kode)
            ->where('permissions.status', 'aktif')
            ->exists();
    }

    public function ownedStores(): HasMany
    {
        return $this->hasMany(Store::class, 'owner_id', 'user_id');
    }

    public function storeAssignments(): HasMany
    {
        return $this->hasMany(StoreStaff::class, 'user_id', 'user_id');
    }

    public function assignedStores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_staff', 'user_id', 'store_id')
            ->withPivot('store_staff_id', 'tanggal_penugasan', 'status')
            ->withTimestamps();
    }

    public function warehouseAssignments(): HasMany
    {
        return $this->hasMany(WarehouseStaff::class, 'user_id', 'user_id');
    }

    public function assignedWarehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_staff', 'user_id', 'warehouse_id')
            ->withPivot('warehouse_staff_id', 'tanggal_penugasan', 'status')
            ->withTimestamps();
    }

    public function wishlist(): HasOne
    {
        return $this->hasOne(Wishlist::class, 'user_id', 'user_id');
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class, 'user_id', 'user_id');
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Checkout::class, 'user_id', 'checkout_id', 'user_id', 'checkout_id');
    }

    public function checkouts(): HasMany
    {
        return $this->hasMany(Checkout::class, 'user_id', 'user_id');
    }

    public function paymentVerifications(): HasMany
    {
        return $this->hasMany(PaymentVerification::class, 'verifier_id', 'user_id');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'creator_id', 'user_id');
    }

    public function refundsRequested(): HasMany
    {
        return $this->hasMany(Refund::class, 'requested_by', 'user_id');
    }

    public function refundsReviewed(): HasMany
    {
        return $this->hasMany(Refund::class, 'reviewed_by', 'user_id');
    }

    public function withdrawalsReviewed(): HasMany
    {
        return $this->hasMany(Withdrawal::class, 'reviewed_by', 'user_id');
    }

    public function productionOrdersRequested(): HasMany
    {
        return $this->hasMany(ProductionOrder::class, 'requested_by', 'user_id');
    }

    public function productionOrdersAssigned(): HasMany
    {
        return $this->hasMany(ProductionOrder::class, 'assigned_to', 'user_id');
    }

    public function qualityChecks(): HasMany
    {
        return $this->hasMany(QualityCheck::class, 'checked_by', 'user_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'dibuat_oleh', 'user_id');
    }

    public function stockTransfersRequested(): HasMany
    {
        return $this->hasMany(StockTransfer::class, 'requested_by', 'user_id');
    }

    public function stockTransfersApproved(): HasMany
    {
        return $this->hasMany(StockTransfer::class, 'approved_by', 'user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id', 'user_id');
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'user_id', 'user_id');
    }

    public function complaintMessages(): HasMany
    {
        return $this->hasMany(ComplaintMessage::class, 'sender_id', 'user_id');
    }

    public function ralivaNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'user_id');
    }
}
