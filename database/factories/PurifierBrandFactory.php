<?php

namespace Database\Factories;

use App\Models\PurifierBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurifierBrandFactory extends Factory
{
    protected $model = PurifierBrand::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'code' => strtoupper($this->faker->unique()->bothify('BRAND-####-??')),
            'status' => 'active',
        ];
    }
}
