<?php

namespace Database\Factories;

use App\Enums\ProductType;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'category_id' => ProductCategory::factory(),
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-######-??')),
            'name' => $this->faker->words(3, true),
            'product_type' => ProductType::FILTER,
            'unit' => 'piece',
            'cost_price' => $this->faker->randomFloat(2, 0, 500000),
            'selling_price' => $this->faker->randomFloat(2, 0, 1000000),
            'replacement_months' => $this->faker->optional()->randomElement([3, 6, 12, 24]),
            'brand_name' => $this->faker->optional()->company,
            'description' => $this->faker->optional()->sentence,
            'compatible_models' => [],
            'status' => 'active',
        ];
    }
}
