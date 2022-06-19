<?php

namespace Database\Seeders;

use App\Enums\Locale;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    private array $countries = [
        [
            "name" => "Venezuela",
            "code" => "VEN",
            "locale" => Locale::SPANISH,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->countries as $country) {
            Country::query()->create($country);
        }
    }
}
