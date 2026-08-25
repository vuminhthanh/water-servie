<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FilterReplacementHistory extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'purifier_filter_id', 'purifier_id', 'old_product_id', 'new_product_id',
        'service_order_id', 'technician_id', 'replaced_at', 'replacement_months',
        'next_replace_at', 'input_tds', 'output_tds', 'old_filter_name',
        'new_filter_name', 'quantity', 'unit_cost', 'unit_price', 'note',
    ];

    protected $casts = [
        'replaced_at' => 'datetime',
        'next_replace_at' => 'date',
        'replacement_months' => 'integer',
        'input_tds' => 'integer',
        'output_tds' => 'integer',
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];

    public function purifierFilter(): BelongsTo
    {
        return $this->belongsTo(PurifierFilter::class);
    }

    public function waterPurifier(): BelongsTo
    {
        return $this->belongsTo(WaterPurifier::class, 'purifier_id');
    }

    public function oldProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'old_product_id')->withTrashed();
    }

    public function newProduct(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'new_product_id')->withTrashed();
    }
    public function serviceOrder(): BelongsTo { return $this->belongsTo(ServiceOrder::class); }
    public function technician(): BelongsTo { return $this->belongsTo(Technician::class); }
}
