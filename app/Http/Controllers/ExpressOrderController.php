<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class ExpressOrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء طلب التوصيل السريع (VIP) بنجاح!',
            'data'    => $order->load('items')
        ], 201);
    }
}
