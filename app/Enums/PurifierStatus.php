<?php

namespace App\Enums;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

final class PurifierStatus implements CastsAttributes
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';
    public const REPLACED = 'replaced';
    public const DISPOSED = 'disposed';

    public $value;

    public function __construct($value = self::ACTIVE)
    {
        if (!in_array($value, self::values(), true)) {
            throw new InvalidArgumentException("Invalid purifier status: {$value}");
        }

        $this->value = $value;
    }

    public static function values(): array
    {
        return [self::ACTIVE, self::INACTIVE, self::REPLACED, self::DISPOSED];
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return $value === null ? null : new self($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof self) {
            return $value->value;
        }

        return (new self($value))->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
