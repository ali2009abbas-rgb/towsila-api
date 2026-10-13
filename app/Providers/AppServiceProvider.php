<?php

namespace App\Providers;

use App\Contracts\DeliveryFeeStrategyInterface;
use App\Services\StandardDeliveryService;
use App\Http\Controllers\ExpressOrderController;
use App\Services\ExpressDeliveryService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
// 1. الربط الافتراضي لأجرة التوصيل القياسية
    $this->app->bind(DeliveryFeeStrategyInterface::class, StandardDeliveryService::class);

    // 2. الربط السياقي (Contextual Binding): إذا تم طلب الخدمة داخل المتحكم السريع، احقن التوصيل السريع تلقائياً
    $this->app->when(ExpressOrderController::class)
        ->needs(DeliveryFeeStrategyInterface::class)
        ->give(ExpressDeliveryService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(!app()->isProduction());
    }
}
