<?php

namespace Database\Factories;

use App\Enums\Locale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->country,
            "code" => $this->faker->countryCode,
            "locale" => $this->faker->randomElement(Locale::values()),
        ];
    }
}
