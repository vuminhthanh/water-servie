<?php

namespace App\Actions\PurifierFilters;

use App\Models\PurifierFilter;
use Carbon\CarbonImmutable;

class CreatePurifierFilter
{
    public function execute(array $attributes): PurifierFilter
    {
        if (!empty($attributes['installed_at'])
            && !empty($attributes['replacement_months'])
            && empty($attributes['next_replace_at'])) {
            $attributes['next_replace_at'] = CarbonImmutable::parse($attributes['installed_at'])
                ->addMonths((int) $attributes['replacement_months'])
                ->toDateString();
        }

        return PurifierFilter::create($attributes);
    }
}
