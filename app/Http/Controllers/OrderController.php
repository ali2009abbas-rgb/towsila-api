<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    ) {}

    /**
     * شاشة الطلبات الحالية (مع استخدام Selective Columns و منع N+1)
     */
    public function index(): JsonResponse
    {
        // استخدام الـ Local Scope الموحد active() مع تحديد أعمدة محددة فقط (Selective Columns) لتسريع الأداء
        $activeOrders = Order::active()
            ->with([
                'customer:id,name',      // جلب معرف ورسالة اسم الزبون فقط دون جلب كل أعمدة الجدول
                'restaurant:id,name',    // جلب معرف واسم المطعم فقط
                'driver:id,name'         // جلب معرف واسم السائق فقط
            ])
            ->select('id', 'customer_id', 'restaurant_id', 'driver_id', 'status', 'total_amount', 'created_at')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $activeOrders
        ], 200);
    }

    /**
     * إنشاء طلب عادي
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الطلب بنجاح!',
            'data'    => $order->load('items')
        ], 201);
    }
}
