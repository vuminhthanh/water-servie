<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition()
    {
        return [
            'parent_id' => null,
            'name' => $this->faker->words(2, true),
            'code' => strtoupper($this->faker->unique()->bothify('CAT-####-??')),
            'description' => $this->faker->optional()->sentence,
            'status' => 'active',
        ];
    }
}
