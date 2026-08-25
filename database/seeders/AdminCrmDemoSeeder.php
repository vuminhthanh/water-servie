<?php

namespace Database\Seeders;

use App\Enums\{PaymentStatus, ProductType, ServiceOrderStatus, ServiceOrderType, TechnicianWorkingStatus};
use App\Models\{Customer,
    CustomerAddress,
    Product,
    ProductCategory,
    PurifierBrand,
    PurifierModel,
    Lead,
    MarketingSource,
    ServiceOrder,
    Technician,
    User,
    WaterPurifier};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminCrmDemoSeeder extends Seeder
{
    public function run()
    {
        if (!app()->environment(['local', 'development'])) throw new RuntimeException('AdminCrmDemoSeeder chỉ được chạy ở local/development.');
        $this->call(VietnamPurifierCatalogSeeder::class);
        $brands = PurifierBrand::whereIn('code', ['KAROFI', 'KANGAROO', 'AO-SMITH', 'COWAY', 'PANASONIC'])->orderBy('id')->get()->all();
        $models = PurifierModel::whereIn('brand_id', collect($brands)->pluck('id'))->orderBy('id')->take(10)->get()->all();
        $this->call(ProductCatalogSeeder::class);
        $customers = [];
        $purifiers = [];
        for ($i = 1; $i <= 10; $i++) {
            $phone = '090000' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $c = Customer::updateOrCreate(['phone' => $phone], ['full_name' => 'Khách hàng demo ' . $i, 'customer_type' => 'individual', 'status' => 'active']);
            $customers[] = $c;
            $a = CustomerAddress::updateOrCreate(['customer_id' => $c->id, 'name' => 'Nhà demo'], ['address_line' => $i . ' Đường Demo, TP.HCM', 'is_default' => true]);
            $purifiers[] = WaterPurifier::updateOrCreate(['serial_number' => 'DEMO-SN-' . $i], ['customer_id' => $c->id, 'address_id' => $a->id, 'brand_id' => $brands[$i % 5]->id, 'model_id' => $models[$i - 1]->id, 'custom_name' => 'Máy demo ' . $i, 'status' => 'active']);
        }
        for ($i = 1; $i <= 3; $i++) {
            $u = User::updateOrCreate(['email' => 'demo.technician' . $i . '@water-service.local'], ['name' => 'Kỹ thuật viên demo ' . $i, 'password' => Hash::make('not-used-for-filament')]);
            Technician::updateOrCreate(['technician_code' => 'DEMO-TECH-' . $i], ['user_id' => $u->id, 'phone' => '091000000' . $i, 'working_status' => TechnicianWorkingStatus::AVAILABLE]);
        }

        $sourceDefinitions = [
            ['code' => 'FACEBOOK', 'name' => 'Facebook', 'channel' => 'facebook'],
            ['code' => 'GOOGLE', 'name' => 'Google Ads', 'channel' => 'google'],
            ['code' => 'ZALO', 'name' => 'Zalo', 'channel' => 'zalo'],
            ['code' => 'REFERRAL', 'name' => 'Khách giới thiệu', 'channel' => 'referral'],
        ];
        $sources = [];
        foreach ($sourceDefinitions as $source) {
            $sources[] = MarketingSource::updateOrCreate(
                ['code' => $source['code']],
                ['name' => $source['name'], 'channel' => $source['channel'], 'status' => 'active']
            );
        }

        $leadStatuses = ['new', 'contacted', 'qualified', 'converted', 'lost'];
        for ($i = 1; $i <= 10; $i++) {
            Lead::updateOrCreate(
                ['phone' => '098000' . str_pad($i, 4, '0', STR_PAD_LEFT)],
                [
                    'full_name' => 'Lead demo ' . $i,
                    'phone_normalized' => '098000' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'source_id' => $sources[($i - 1) % count($sources)]->id,
                    'campaign' => 'Chiến dịch demo ' . (($i - 1) % 3 + 1),
                    'requirement' => $i % 2 === 0 ? 'Tư vấn thay lõi lọc' : 'Kiểm tra máy lọc nước',
                    'status' => $leadStatuses[($i - 1) % count($leadStatuses)],
                    'customer_id' => $i % 5 === 4 ? $customers[$i - 1]->id : null,
                ]
            );
        }

        $statuses = ServiceOrderStatus::values();
        for ($i = 1; $i <= 20; $i++) ServiceOrder::updateOrCreate(['order_code' => 'DEMO-SO-' . str_pad($i, 3, '0', STR_PAD_LEFT)], ['customer_id' => $customers[($i - 1) % 10]->id, 'purifier_id' => $purifiers[($i - 1) % 10]->id, 'address_id' => $purifiers[($i - 1) % 10]->address_id, 'service_address' => $purifiers[($i - 1) % 10]->address->address_line, 'order_type' => ServiceOrderType::MAINTENANCE, 'status' => $statuses[($i - 1) % count($statuses)], 'scheduled_at' => now()->startOfDay()->addDays(($i % 7) - 3)->addHours(8 + $i % 8), 'completed_at' => $statuses[($i - 1) % count($statuses)] === ServiceOrderStatus::COMPLETED ? now() : null, 'total_amount' => 200000 + $i * 10000, 'payment_status' => $i % 3 === 0 ? PaymentStatus::PAID : PaymentStatus::UNPAID]);
    }
}
