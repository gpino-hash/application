<?php

namespace App\UseCase\ExchangeRate\Impl;

use App\Services\ExchangeRate\ExchangeRateVen;
use App\UseCase\ExchangeRate\ExchangeRate;

class DefaultExchangeRate implements ExchangeRate
{

    /**
     * @inheritDoc
     */
    public function get(string $country): string
    {
        return match ($country) {
            "VE" => new ExchangeRateVen(),
        };
    }
}