<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING   = 'pending';   // قيد الانتظار
    case ACCEPTED  = 'accepted';  // قُبل من المحل
    case PICKED_UP = 'picked_up'; // استلمه السائق
    case DELIVERED = 'delivered'; // تم التوصيل (منتهي)
    case CANCELLED = 'cancelled'; // ملغى (منتهي)

    public function label(): string
    {
        return match($this) {
            self::PENDING   => 'قيد الانتظار',
            self::ACCEPTED  => 'مقبول',
            self::PICKED_UP => 'مع السائق في الطريق',
            self::DELIVERED => 'تم التوصيل',
            self::CANCELLED => 'ملغى',
        };
    }
}
