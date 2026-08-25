<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurifierBrand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'status'];

    public function purifierModels(): HasMany
    {
        return $this->hasMany(PurifierModel::class, 'brand_id');
    }

    public function waterPurifiers(): HasMany
    {
        return $this->hasMany(WaterPurifier::class, 'brand_id');
    }
}
