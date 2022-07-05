<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    private array $currencies = [
        [
            "name" => "Bolivar",
            "description" => "Moneda venezolana",
            "symbol" => "Bs",
            "code" => "VED",
            "decimal_places" => 2,
        ],
        [
            "name" => "U.S. dollar",
            "description" => "Dolar norteamericano",
            "symbol" => "$",
            "code" => "USD",
            "decimal_places" => 2,
        ],
        /*[
            "name" => "Euro",
            "description" => "Moneda europea",
            "symbol" => "€",
            "code" => "EUR",
            "decimal_places" => 2,
        ],
        [
            "name" => "U.S. Latam",
            "description" => "Dolar norteamericano",
            "symbol" => 'U$D',
            "code" => "USD",
            "decimal_places" => 2,
        ],
        [
            "name" => "Peso",
            "description" => "Peso Argentino",
            "symbol" => '$',
            "code" => "ARS",
            "decimal_places" => 2,
        ],*/
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countryId = Country::query()->first()->uuid;
        foreach ($this->currencies as $currency) {
            Currency::query()->create(array_merge(["country_uuid" => $countryId], $currency));
        }
    }
}
