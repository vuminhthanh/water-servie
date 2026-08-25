<?php

namespace App\Models;

use App\Enums\PurifierFilterStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurifierFilter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purifier_id', 'product_id', 'filter_position', 'filter_name', 'installed_at',
        'last_replace_at', 'replacement_months', 'next_replace_at', 'status', 'note',
    ];

    protected $casts = [
        'installed_at' => 'date',
        'last_replace_at' => 'date',
        'next_replace_at' => 'date',
        'replacement_months' => 'integer',
        'status' => PurifierFilterStatus::class,
    ];

    public function waterPurifier(): BelongsTo
    {
        return $this->belongsTo(WaterPurifier::class, 'purifier_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function replacementHistories(): HasMany
    {
        return $this->hasMany(FilterReplacementHistory::class);
    }
}
