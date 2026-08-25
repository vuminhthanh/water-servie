<?php

namespace App\Enums;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

final class PurifierType implements CastsAttributes
{
    public const RO = 'ro';
    public const NANO = 'nano';
    public const UF = 'uf';
    public const WHOLE_HOUSE = 'whole_house';
    public const OTHER = 'other';

    public $value;

    public function __construct($value = self::RO)
    {
        if (!in_array($value, self::values(), true)) {
            throw new InvalidArgumentException("Invalid purifier type: {$value}");
        }

        $this->value = $value;
    }

    public static function values(): array
    {
        return [self::RO, self::NANO, self::UF, self::WHOLE_HOUSE, self::OTHER];
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
