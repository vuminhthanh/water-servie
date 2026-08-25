<?php

namespace Database\Factories;

use App\Enums\PurifierType;
use App\Models\PurifierBrand;
use App\Models\PurifierModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurifierModelFactory extends Factory
{
    protected $model = PurifierModel::class;

    public function definition()
    {
        return [
            'brand_id' => PurifierBrand::factory(),
            'name' => $this->faker->words(2, true),
            'model_code' => strtoupper($this->faker->unique()->bothify('MODEL-####-??')),
            'purifier_type' => $this->faker->randomElement(PurifierType::values()),
            'number_of_filters' => $this->faker->numberBetween(1, 12),
            'note' => $this->faker->optional()->sentence,
        ];
    }
}
