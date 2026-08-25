<?php

namespace App\Models;

use App\Services\CustomerCodeGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    public const TYPE_INDIVIDUAL = 'individual';
    public const TYPE_COMPANY = 'company';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_BLOCKED = 'blocked';

    protected $fillable = [
        'customer_code', 'full_name', 'phone', 'phone_normalized', 'email',
        'customer_type', 'company_name', 'tax_code', 'source_id', 'note',
        'status', 'last_service_at', 'next_service_at', 'created_by',
    ];

    protected $casts = [
        'last_service_at' => 'datetime',
        'next_service_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (Customer $customer) {
            if (!$customer->customer_code) {
                $customer->customer_code = app(CustomerCodeGenerator::class)->temporary();
            }
        });

        static::created(function (Customer $customer) {
            if (strpos($customer->customer_code, 'KH_TMP_') !== 0) {
                return;
            }

            $customer->customer_code = app(CustomerCodeGenerator::class)->generate($customer);
            $customer->saveQuietly();
        });
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function waterPurifiers(): HasMany
    {
        return $this->hasMany(WaterPurifier::class);
    }
    public function serviceOrders(): HasMany { return $this->hasMany(ServiceOrder::class); }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(MarketingSource::class, 'source_id');
    }

    public function getInitialAddressAttribute()
    {
        return $this->attributes['initial_address'] ?? $this->addresses()
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->value('address_line');
    }

    public function setInitialAddressAttribute($value): void
    {
        $this->attributes['initial_address'] = $value;
    }

    public function clearInitialAddressInput(): void
    {
        unset($this->attributes['initial_address']);
    }
}
