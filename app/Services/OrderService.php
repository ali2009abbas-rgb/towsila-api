<?php

namespace App\Services;

use App\Contracts\DeliveryFeeStrategyInterface;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected DeliveryFeeStrategyInterface $deliveryFeeStrategy
    ) {}

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // 1. حساب إجمالي مبلغ المنتجات
            $totalAmount = 0;
            foreach ($data['items'] as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            // 2. حساب أجرة التوصيل عبر الاستراتيجية المحقونة (عادي أو سريع)
            $deliveryFee = $this->deliveryFeeStrategy->calculateFee($totalAmount);

            // 3. إنشاء الطلب الرئيسي
            $order = Order::create([
                'customer_id'   => $data['customer_id'],
                'restaurant_id' => $data['restaurant_id'],
                'status'        => OrderStatus::PENDING,
                'total_amount'  => $totalAmount,
                'delivery_fee'  => $deliveryFee,
            ]);

            // 4. إنشاء العناصر المرتبطة بالطلب
            foreach ($data['items'] as $item) {
                $order->items()->create($item);
            }

            return $order;
        });
    }
}
