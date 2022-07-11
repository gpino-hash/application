<?php

namespace App\Services\ExchangeRate;

use Illuminate\Support\Facades\Http;

class ExchangeRateVen
{
    private const URL = "https://s3.amazonaws.com/dolartoday/data.json";

    /**
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function apply()
    {
        $exchange = Http::get(self::URL)->throw();
    }
}