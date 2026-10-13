<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ExpressOrderController;
use Illuminate\Support\Facades\Route;

// جلب الطلبات الحالية الفعالة (خالية من N+1 وبأداء محسن)
Route::get('/orders/active', [OrderController::class, 'index']);

// إنشاء طلب عادي
Route::post('/orders', [OrderController::class, 'store']);

// إنشاء طلب سريع / VIP (يطبق Contextual Binding تلقائياً)
Route::post('/express-orders', [ExpressOrderController::class, 'store']);
