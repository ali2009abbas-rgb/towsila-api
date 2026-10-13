<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete(); // RESTRICT للحفاظ على التاريخ
            $table->foreignId('restaurant_id')->constrained()->restrictOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('pending'); // Enum: pending, accepted, picked_up, delivered, cancelled
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);

            // Indexes مبررة للأداء والطلب الفعال:
            // 1. فهرس مركّب على (status, created_at) لتسريع استعلام الطلبات الفعالة المفتوحة والفرز الزمني
            $table->index(['status', 'created_at']);
            // 2. فهرس على driver_id للبحث السريع عن طلبات سائق معين
            $table->index('driver_id');
            // 3. فهرس على restaurant_id لتقارير مطعم معين
            $table->index('restaurant_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
