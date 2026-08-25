<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'sku', 'name', 'product_type', 'unit', 'cost_price',
        'selling_price', 'stock_quantity', 'low_stock_threshold', 'replacement_months', 'brand_name', 'description', 'image_path',
        'compatible_models', 'status',
    ];

    protected $casts = [
        'product_type' => ProductType::class,
        'compatible_models' => 'array',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock_quantity' => 'decimal:2',
        'low_stock_threshold' => 'decimal:2',
        'replacement_months' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function purifierFilters(): HasMany
    {
        return $this->hasMany(PurifierFilter::class);
    }

    public function oldReplacementHistories(): HasMany
    {
        return $this->hasMany(FilterReplacementHistory::class, 'old_product_id');
    }

    public function newReplacementHistories(): HasMany
    {
        return $this->hasMany(FilterReplacementHistory::class, 'new_product_id');
    }

    public function serviceOrderItems(): HasMany { return $this->hasMany(ServiceOrderItem::class); }
    public function inventoryMovements(): HasMany { return $this->hasMany(InventoryMovement::class); }
    public function waterPurifiers(): HasMany { return $this->hasMany(WaterPurifier::class); }
}
