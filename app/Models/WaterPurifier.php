<?php

namespace App\Models;

use App\Enums\PurifierStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterPurifier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id', 'address_id', 'product_id', 'installation_address', 'brand_id', 'model_id', 'serial_number',
        'custom_name', 'image_path', 'installed_at', 'purchased_at', 'last_service_at',
        'next_service_at', 'water_input_tds', 'water_output_tds', 'status', 'note',
    ];

    protected $casts = [
        'installed_at' => 'date',
        'purchased_at' => 'date',
        'last_service_at' => 'datetime',
        'next_service_at' => 'datetime',
        'water_input_tds' => 'integer',
        'water_output_tds' => 'integer',
        'status' => PurifierStatus::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, 'address_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function getCustomNameAttribute($value)
    {
        return optional($this->product)->name ?: $value;
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(PurifierBrand::class, 'brand_id');
    }

    public function purifierModel(): BelongsTo
    {
        return $this->belongsTo(PurifierModel::class, 'model_id');
    }

    public function purifierFilters(): HasMany
    {
        return $this->hasMany(PurifierFilter::class, 'purifier_id');
    }

    public function filterReplacementHistories(): HasMany
    {
        return $this->hasMany(FilterReplacementHistory::class, 'purifier_id');
    }
    public function serviceOrders(): HasMany { return $this->hasMany(ServiceOrder::class, 'purifier_id'); }
}
