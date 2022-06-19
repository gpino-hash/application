<?php

namespace App\Enums\Traits;

trait HasValues
{
    public static function values(): array
    {
        return collect(self::cases())
            ->map(fn(self $case) => $case->value)
            ->toArray();
    }
}