<?php

namespace App\Contracts;

interface DeliveryFeeStrategyInterface
{
    public function calculateFee(float $orderTotal): float;
}
