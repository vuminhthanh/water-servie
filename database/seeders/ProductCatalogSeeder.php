<?php

namespace Database\Seeders;

use App\Enums\ProductType;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCatalogSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            DB::table('products')->where('sku', 'like', 'DEMO-SKU-%')->delete();
            DB::table('products')->whereIn('sku', [
                'FILTER-PP-5M', 'FILTER-GAC', 'FILTER-CTO',
                'FILTER-RO-100G', 'FILTER-POST-CARBON',
            ])->delete();

            $categories = [
                'FILTER-SEDIMENT' => ['Lõi lọc thô', 'Lõi PP và lõi giữ cặn bẩn, bùn đất, rỉ sét.'],
                'FILTER-CARBON' => ['Lõi than hoạt tính', 'Lõi GAC, CTO và lõi cải thiện mùi vị.'],
                'FILTER-RO' => ['Màng lọc RO', 'Màng thẩm thấu ngược và lõi RO tích hợp.'],
                'FILTER-UF' => ['Màng lọc UF', 'Lõi màng siêu lọc sợi rỗng.'],
                'FILTER-MINERAL' => ['Lõi khoáng', 'Lõi bổ sung khoáng sau lọc.'],
                'FILTER-ALKALINE' => ['Lõi kiềm và Hydrogen', 'Lõi hỗ trợ cân bằng pH hoặc tạo Hydrogen.'],
                'FILTER-ANTIBACTERIAL' => ['Lõi kháng khuẩn', 'Lõi Nano Silver và lõi kiểm soát tái nhiễm khuẩn.'],
                'PART' => ['Linh kiện', 'Linh kiện máy lọc nước.'],
                'MACHINE' => ['Máy lọc nước', 'Máy lọc nước nguyên chiếc.'],
                'SERVICE' => ['Dịch vụ', 'Dịch vụ kiểm tra, bảo trì và thay lõi.'],
            ];

            $categoryModels = [];
            foreach ($categories as $code => [$name, $description]) {
                $categoryModels[$code] = ProductCategory::updateOrCreate(
                    ['code' => $code],
                    ['name' => $name, 'description' => $description, 'status' => 'active']
                );
            }

            ProductCategory::query()->where('code', 'FILTER')->delete();

            $products = [
                ['KAROFI-SMAX-PRO-V1', 'Karofi Smax Pro V1', 'FILTER-SEDIMENT', 'Karofi', 12, ['KAQ-U05-PRO']],
                ['KAROFI-SMAX-PRO-V2', 'Karofi Smax Pro V2', 'FILTER-CARBON', 'Karofi', 12, ['KAQ-U05-PRO']],
                ['KAROFI-SMAX-PRO-V3', 'Karofi Smax Pro V3', 'FILTER-SEDIMENT', 'Karofi', 12, ['KAQ-U05-PRO']],
                ['KAROFI-SMAX-RO-100G', 'Màng RO Karofi Smax 100 GPD', 'FILTER-RO', 'Karofi', 36, ['KAQ-U05', 'KAQ-U05-PRO']],
                ['KANGAROO-ECO-1', 'Lõi Kangaroo Eco số 1', 'FILTER-SEDIMENT', 'Kangaroo', 6, ['KG109I', 'KG111']],
                ['KANGAROO-ECO-2', 'Lõi Kangaroo Eco số 2', 'FILTER-CARBON', 'Kangaroo', null, ['KG109I', 'KG111']],
                ['KANGAROO-RO-VORTEX', 'Màng RO Vortex Kangaroo', 'FILTER-RO', 'Kangaroo', null, ['KGMC4RO', 'KG109I', 'KG-Y1MED']],
                ['KANGAROO-ALKALINE-4', 'Bộ 4 lõi chức năng Alkaline Kangaroo', 'FILTER-ALKALINE', 'Kangaroo', null, ['KG109I', 'KG111']],
                ['AOS-X1000-COMPOSITE', 'Lõi Composite 6 trong 1 A. O. Smith X1000', 'FILTER-RO', 'A. O. Smith', null, ['X1000']],
                ['PANASONIC-TK-CJ600C-EX', 'Lõi Panasonic MicroClear4000 TK-CJ600C-EX', 'FILTER-CARBON', 'Panasonic', 12, ['TK-CJ300-WVN', 'TK-CJ600-ZVN']],
            ];

            foreach ($products as [$sku, $name, $categoryCode, $brand, $months, $models]) {
                Product::updateOrCreate(['sku' => $sku], [
                    'category_id' => $categoryModels[$categoryCode]->id,
                    'name' => $name,
                    'product_type' => ProductType::FILTER,
                    'unit' => 'piece',
                    'cost_price' => 0,
                    'selling_price' => 0,
                    'replacement_months' => $months,
                    'brand_name' => $brand,
                    'compatible_models' => $models,
                    'description' => 'Catalog linh kiện chính hãng tại Việt Nam, rà soát ngày 24/08/2026. Chu kỳ thực tế phụ thuộc chất lượng nước và mức sử dụng.',
                    'status' => 'active',
                ]);
            }
        });
    }
}
