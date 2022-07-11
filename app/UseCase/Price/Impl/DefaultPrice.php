<?php

namespace App\UseCase\Price\Impl;

use App\Models\Currency;
use App\UseCase\Price\Price;

class DefaultPrice implements Price
{

    public function apply(string $price, Currency $localCurrency, Currency $currency): mixed
    {
        if ($currency->uuid === $localCurrency->uuid) {
            return number_format(floatval($price),
                $currency->decimal_places,
                $currency->decimal_separator,
                $currency->thousands_separator);
        }
        return null;
    }
}