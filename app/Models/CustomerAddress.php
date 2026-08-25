<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'name', 'contact_name', 'contact_phone', 'province_code',
        'district_code', 'ward_code', 'address_line', 'latitude', 'longitude',
        'is_default', 'note',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_default' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function waterPurifiers(): HasMany
    {
        return $this->hasMany(WaterPurifier::class, 'address_id');
    }
    public function serviceOrders(): HasMany { return $this->hasMany(ServiceOrder::class, 'address_id'); }
}
