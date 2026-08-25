<?php

namespace Database\Factories;

use App\Enums\PurifierStatus;
use App\Models\Customer;
use App\Models\PurifierBrand;
use App\Models\PurifierModel;
use App\Models\Product;
use App\Models\WaterPurifier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WaterPurifierFactory extends Factory
{
    protected $model = WaterPurifier::class;

    public function definition()
    {
        return [
            'customer_id' => function () {
                return Customer::create([
                    'customer_code' => 'CUS-'.Str::upper(Str::random(10)),
                    'full_name' => $this->faker->name,
                    'phone' => $this->faker->numerify('09########'),
                    'customer_type' => Customer::TYPE_INDIVIDUAL,
                    'status' => Customer::STATUS_ACTIVE,
                ])->id;
            },
            'address_id' => null,
            'product_id' => Product::factory()->state(['product_type' => 'machine']),
            'brand_id' => PurifierBrand::factory(),
            'model_id' => function (array $attributes) {
                return PurifierModel::factory()->create(['brand_id' => $attributes['brand_id']])->id;
            },
            'serial_number' => strtoupper($this->faker->unique()->bothify('SN-########')),
            'custom_name' => $this->faker->optional()->words(2, true),
            'installed_at' => $this->faker->optional()->date(),
            'purchased_at' => $this->faker->optional()->date(),
            'last_service_at' => $this->faker->optional()->dateTime(),
            'next_service_at' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'water_input_tds' => $this->faker->optional()->numberBetween(50, 1000),
            'water_output_tds' => $this->faker->optional()->numberBetween(1, 100),
            'status' => PurifierStatus::ACTIVE,
            'note' => $this->faker->optional()->sentence,
        ];
    }
}
