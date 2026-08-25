<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingSource extends Model
{
    use HasFactory;

    public const CHANNEL_GOOGLE = 'google';
    public const CHANNEL_FACEBOOK = 'facebook';
    public const CHANNEL_TIKTOK = 'tiktok';
    public const CHANNEL_ZALO = 'zalo';
    public const CHANNEL_SEO = 'seo';
    public const CHANNEL_REFERRAL = 'referral';
    public const CHANNEL_OFFLINE = 'offline';
    public const CHANNEL_OTHER = 'other';

    protected $fillable = ['name', 'code', 'channel', 'status'];

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'source_id');
    }
}
