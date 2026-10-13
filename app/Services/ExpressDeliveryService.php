<?php

namespace App\Services;

use App\Contracts\DeliveryFeeStrategyInterface;

class ExpressDeliveryService implements DeliveryFeeStrategyInterface
{
    public function calculateFee(float $orderTotal): float
    {
        // أجرة توصيل سريعة تتأثر بقيمة الطلب
        return 10000.00 + ($orderTotal * 0.05);
    }
}
