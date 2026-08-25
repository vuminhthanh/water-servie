<?php

namespace Database\Seeders;

use App\Models\PurifierBrand;
use App\Models\PurifierModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VietnamPurifierCatalogSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            PurifierModel::query()->where('model_code', 'like', 'DEMO-MODEL-%')->delete();
            PurifierBrand::query()->where('code', 'like', 'DEMO-BRAND-%')->delete();

            $catalog = [
                ['brand' => ['code' => 'KAROFI', 'name' => 'Karofi'], 'models' => [
                    ['KAQ-U05', 'Karofi KAQ-U05', 'ro', 10],
                    ['KAQ-U05-PRO', 'Karofi KAQ-U05 Pro', 'ro', 10],
                    ['KAQ-U50K', 'Karofi KAQ-U50K', 'ro', null],
                    ['KAD-D66S-PRO', 'Karofi KAD-D66S Pro', 'ro', null],
                ]],
                ['brand' => ['code' => 'KANGAROO', 'name' => 'Kangaroo'], 'models' => [
                    ['KGMC4RO', 'Kangaroo Macca KGMC4RO', 'ro', 4],
                    ['KG109I', 'Kangaroo Infinity KG109I', 'ro', 9],
                    ['KG-Y1MED', 'Kangaroo Hydrogen ion kiềm KG-Y1MED', 'ro', 5],
                    ['KG111', 'Kangaroo Hydrogen KG111', 'ro', null],
                ]],
                ['brand' => ['code' => 'AO-SMITH', 'name' => 'A. O. Smith'], 'models' => [
                    ['X1000', 'A. O. Smith X1000', 'ro', 2],
                    ['R700SLIM', 'A. O. Smith R700Slim', 'ro', 2],
                    ['S400', 'A. O. Smith S400', 'ro', null],
                    ['VITA', 'A. O. Smith VITA', 'ro', null],
                ]],
                ['brand' => ['code' => 'COWAY', 'name' => 'Coway'], 'models' => [
                    ['P-300R', 'Coway NADI P-300R', 'ro', 5],
                ]],
                ['brand' => ['code' => 'PANASONIC', 'name' => 'Panasonic'], 'models' => [
                    ['TK-CB430-ZEX', 'Panasonic TK-CB430', 'uf', 3],
                    ['TK-CJ300-WVN', 'Panasonic TK-CJ300', 'other', 1],
                    ['TK-CJ600-ZVN', 'Panasonic TK-CJ600', 'other', 1],
                    ['TK-CA811K-VN', 'Panasonic TK-CA811K', 'ro', null],
                    ['TK-CA812M-VN', 'Panasonic TK-CA812M', 'ro', null],
                    ['TK-CA813F-VN', 'Panasonic TK-CA813F', 'ro', 7],
                    ['FP-15AA1M', 'Panasonic FP-15AA1M', 'whole_house', null],
                ]],
            ];

            foreach ($catalog as $entry) {
                $brand = PurifierBrand::updateOrCreate(
                    ['code' => $entry['brand']['code']],
                    ['name' => $entry['brand']['name'], 'status' => 'active']
                );

                foreach ($entry['models'] as [$code, $name, $type, $filters]) {
                    PurifierModel::updateOrCreate(
                        ['brand_id' => $brand->id, 'model_code' => $code],
                        [
                            'name' => $name,
                            'purifier_type' => $type,
                            'number_of_filters' => $filters,
                            'note' => 'Catalog thị trường Việt Nam, rà soát từ website chính hãng ngày 24/08/2026.',
                        ]
                    );
                }
            }
        });
    }
}
