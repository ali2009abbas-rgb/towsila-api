<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'restaurant_id', 'driver_id',
        'status', 'total_amount', 'delivery_fee'
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    // --- Local Scopes المركزية الموحدة ---

    /**
     * شرط "الطلب الفعّال": الطلب الذي لم يتم توصيله ولم يُلغَ نهائياً
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            OrderStatus::DELIVERED->value,
            OrderStatus::CANCELLED->value
        ]);
    }

    /**
     * طلبات سائق معين
     */
    public function scopeForDriver(Builder $query, int $driverId): Builder
    {
        return $query->where('driver_id', $driverId);
    }

    /**
     * الطلبات غير المسندة لسائق بعد
     */
    public function scopeUnassigned(Builder $query): Builder
    {
        return $query->whereNull('driver_id');
    }

    /**
     * طلبات اليوم الحالي فقط
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    // العلاقات
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
