<?php

namespace App\Enums;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

final class PurifierFilterStatus implements CastsAttributes
{
    public const ACTIVE = 'active';
    public const EXPIRED = 'expired';
    public const REMOVED = 'removed';

    public $value;

    public function __construct($value = self::ACTIVE)
    {
        if (!in_array($value, self::values(), true)) {
            throw new InvalidArgumentException("Invalid purifier filter status: {$value}");
        }
        $this->value = $value;
    }

    public static function values(): array
    {
        return [self::ACTIVE, self::EXPIRED, self::REMOVED];
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return $value === null ? null : new self($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value instanceof self ? $value->value : (new self($value))->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
