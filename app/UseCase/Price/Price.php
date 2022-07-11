<?php

namespace App\UseCase\Price;

use App\Models\Currency;

interface Price
{
    /**
     * @param string $price
     * @param Currency $localCurrency
     * @param Currency $currency
     * @return string
     */
    public function apply(string $price, Currency $localCurrency, Currency $currency): mixed;
}