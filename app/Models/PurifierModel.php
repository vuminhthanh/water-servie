<?php

namespace App\Models;

use App\Enums\PurifierType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurifierModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand_id', 'name', 'model_code', 'purifier_type', 'number_of_filters', 'note',
    ];

    protected $casts = [
        'purifier_type' => PurifierType::class,
        'number_of_filters' => 'integer',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(PurifierBrand::class, 'brand_id');
    }

    public function waterPurifiers(): HasMany
    {
        return $this->hasMany(WaterPurifier::class, 'model_id');
    }
}
