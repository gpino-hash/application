<?php

namespace App\UseCase\ExchangeRate;

interface ExchangeRate
{
    /**
     * @param string $country
     * @return string
     */
    public function get(string $country): string;
}