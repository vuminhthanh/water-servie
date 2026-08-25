<?php

namespace Database\Factories;

use App\Models\FilterReplacementHistory;
use App\Models\Product;
use App\Models\PurifierFilter;
use Illuminate\Database\Eloquent\Factories\Factory;

class FilterReplacementHistoryFactory extends Factory
{
    protected $model = FilterReplacementHistory::class;

    public function definition()
    {
        return [
            'purifier_filter_id' => PurifierFilter::factory(),
            'purifier_id' => function (array $attributes) {
                return PurifierFilter::findOrFail($attributes['purifier_filter_id'])->purifier_id;
            },
            'old_product_id' => Product::factory(),
            'new_product_id' => Product::factory(),
            'service_order_id' => null,
            'technician_id' => null,
            'replaced_at' => $this->faker->dateTime(),
            'replacement_months' => 6,
            'next_replace_at' => $this->faker->date(),
            'input_tds' => $this->faker->optional()->numberBetween(50, 1000),
            'output_tds' => $this->faker->optional()->numberBetween(1, 100),
            'old_filter_name' => $this->faker->words(2, true),
            'new_filter_name' => $this->faker->words(2, true),
            'quantity' => 1,
            'unit_cost' => 0,
            'unit_price' => 0,
            'note' => $this->faker->optional()->sentence,
        ];
    }
}
