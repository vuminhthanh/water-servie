<?php

namespace Tests\Feature;

use App\Enums\PurifierStatus;
use App\Enums\PurifierType;
use App\Models\Customer;
use App\Models\PurifierBrand;
use App\Models\PurifierModel;
use App\Models\WaterPurifier;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class WaterPurifierManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_manages_water_purifier_relationships_and_soft_deletes(): void
    {
        $brand = PurifierBrand::factory()->create();
        $purifierModel = PurifierModel::factory()->create([
            'brand_id' => $brand->id,
            'purifier_type' => PurifierType::RO,
        ]);
        $customer = Customer::create([
            'customer_code' => 'TEST-'.Str::upper(Str::random(10)),
            'full_name' => 'Test Customer',
            'phone' => '0900000000',
            'customer_type' => Customer::TYPE_INDIVIDUAL,
            'status' => Customer::STATUS_ACTIVE,
        ]);
        $waterPurifier = WaterPurifier::factory()->create([
            'customer_id' => $customer->id,
            'brand_id' => $brand->id,
            'model_id' => $purifierModel->id,
            'status' => PurifierStatus::ACTIVE,
        ]);

        $this->assertTrue($purifierModel->brand->is($brand));
        $this->assertTrue($brand->purifierModels->contains($purifierModel));
        $this->assertTrue($waterPurifier->customer->is($customer));
        $this->assertTrue($waterPurifier->brand->is($brand));
        $this->assertTrue($waterPurifier->purifierModel->is($purifierModel));
        $this->assertTrue($customer->waterPurifiers->contains($waterPurifier));
        $this->assertSame(PurifierType::RO, $purifierModel->purifier_type->value);
        $this->assertSame(PurifierStatus::ACTIVE, $waterPurifier->status->value);

        $waterPurifier->delete();

        $this->assertSoftDeleted('water_purifiers', ['id' => $waterPurifier->id]);
        $this->assertNull(WaterPurifier::find($waterPurifier->id));
        $this->assertNotNull(WaterPurifier::withTrashed()->find($waterPurifier->id));
    }
}
