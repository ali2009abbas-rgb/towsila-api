<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء مطاعم افتراضية (بعضها نشط وبعضها موقوف لاختبار الـ Global Scope)
        $restaurants = [];
        for ($i = 1; $i <= 10; $i++) {
            $restaurants[] = Restaurant::create([
                'name'      => "مطعم رقم {$i}",
                'phone'     => '09110000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'is_active' => $i <= 8, // أول 8 مطاعم نشطة، والمطعمين الأخيرين موقوفان
            ]);
        }

        // 2. إنشاء زبائن
        $customers = [];
        for ($i = 1; $i <= 50; $i++) {
            $customers[] = Customer::create([
                'name'  => "زبون رقم {$i}",
                'phone' => '09220000' . str_pad($i, 2, '0', STR_PAD_LEFT),
            ]);
        }

        // 3. إنشاء سائقين
        $drivers = [];
        for ($i = 1; $i <= 15; $i++) {
            $drivers[] = Driver::create([
                'name'         => "سائق رقم {$i}",
                'phone'        => '09330000' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'is_available' => true,
            ]);
        }

        // 4. توليد آلاف الطلبات (مثلاً 1000 طلب) دفعة واحدة لضمان السرعة عبر الـ Database Transactions
        $statuses = ['pending', 'accepted', 'picked_up', 'delivered', 'cancelled'];

        $this->command->info('جاري توليد 1000 طلب افتراضي لاختبار الأداء...');

        DB::transaction(function () use ($restaurants, $customers, $drivers, $statuses) {
            for ($i = 1; $i <= 1000; $i++) {
                $restaurant = $restaurants[array_rand($restaurants)];
                $customer   = $customers[array_rand($customers)];
                $driver     = $drivers[array_rand($drivers)];
                $status     = $statuses[array_rand($statuses)];

                $order = Order::create([
                    'customer_id'   => $customer->id,
                    'restaurant_id' => $restaurant->id,
                    'driver_id'     => $driver->id,
                    'status'        => $status,
                    'total_amount'  => rand(25000, 150000),
                    'delivery_fee'  => 5000,
                    'created_at'    => now()->subMinutes(rand(1, 1440)), // طلبات موزعة خلال اليوم الأخير
                ]);

                // إضافة عنصرين إلى ثلاثة عناصر لكل طلب
                for ($j = 1; $j <= rand(1, 3); $j++) {
                    $order->items()->create([
                        'item_name' => "وجبة رقم {$j}",
                        'quantity'  => rand(1, 4),
                        'price'     => rand(5000, 25000),
                    ]);
                }
            }
        });

        $this->command->info('تم إنجاز توليد البيانات بنجاح!');
    }
}
