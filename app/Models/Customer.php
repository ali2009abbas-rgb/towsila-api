<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
    ];

    /**
     * علاقة الزبون بالطلبات (الزبون يمكن أن يطلب عدة طلبات)
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
