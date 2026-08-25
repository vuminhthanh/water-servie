<?php

namespace Database\Factories;

use App\Enums\PurifierFilterStatus;
use App\Models\Product;
use App\Models\PurifierFilter;
use App\Models\WaterPurifier;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurifierFilterFactory extends Factory
{
    protected $model = PurifierFilter::class;

    public function definition()
    {
        return [
            'purifier_id' => WaterPurifier::factory(),
            'product_id' => Product::factory(),
            'filter_position' => $this->faker->numberBetween(1, 10),
            'filter_name' => $this->faker->words(2, true),
            'installed_at' => $this->faker->optional()->date(),
            'last_replace_at' => null,
            'replacement_months' => $this->faker->optional()->randomElement([3, 6, 12, 24]),
            'next_replace_at' => null,
            'status' => PurifierFilterStatus::ACTIVE,
            'note' => $this->faker->optional()->sentence,
        ];
    }
}
