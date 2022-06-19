<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->name,
            "description" => $this->faker->sentence,
            "symbol" => $this->faker->currencyCode(),
            "code" => $this->faker->currencyCode(),
            "decimal_places" => $this->faker->randomDigit(),
        ];
    }
}
