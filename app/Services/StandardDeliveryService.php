<?php

namespace App\Services;

use App\Contracts\DeliveryFeeStrategyInterface;

class StandardDeliveryService implements DeliveryFeeStrategyInterface
{
    public function calculateFee(float $orderTotal): float
    {
        // أجرة توصيل ثابتة للمنطقة العادية (مثلاً 5000)
        return 5000.00;
    }
}
