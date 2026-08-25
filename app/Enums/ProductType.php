<?php

namespace App\Enums;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

final class ProductType implements CastsAttributes
{
    public const FILTER = 'filter';
    public const PART = 'part';
    public const MACHINE = 'machine';
    public const SERVICE = 'service';
    public const OTHER = 'other';

    public $value;

    public function __construct($value = self::OTHER)
    {
        if (!in_array($value, self::values(), true)) {
            throw new InvalidArgumentException("Invalid product type: {$value}");
        }
        $this->value = $value;
    }

    public static function values(): array
    {
        return [self::FILTER, self::PART, self::MACHINE, self::SERVICE, self::OTHER];
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
