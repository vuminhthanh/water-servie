<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    public const STATUS_NEW = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_QUALIFIED = 'qualified';
    public const STATUS_CONVERTED = 'converted';
    public const STATUS_LOST = 'lost';

    protected $fillable = [
        'full_name', 'phone', 'phone_normalized', 'email', 'source_id',
        'campaign', 'medium', 'keyword', 'requirement', 'status', 'customer_id',
        'assigned_to', 'contacted_at', 'converted_at',
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    public function marketingSource(): BelongsTo
    {
        return $this->belongsTo(MarketingSource::class, 'source_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function serviceOrders(): HasMany { return $this->hasMany(ServiceOrder::class); }
}
