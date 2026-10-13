<?php

namespace App\Models;

use App\Models\Scopes\ActiveRestaurantScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// في أحدث إصدارات Laravel و PHP 8.3+، نستخدم الـ Attributes لتطبيق الـ Global Scope بشكل أنظف
#[ScopedBy([ActiveRestaurantScope::class])]
class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean', // تحويل القيمة تلقائياً إلى true/false في PHP
    ];

    /**
     * علاقة المطعم بالطلبات (المطعم الواحد يملك عدة طلبات)
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
