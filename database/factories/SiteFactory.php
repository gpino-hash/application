<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Site>
 */
class SiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->word,
            "branch_office" => "test",
            "symbol" => "TEST",
            "status" => $this->faker->randomElement(Status::values()),
            "country_uuid" => Country::factory(),
        ];
    }
}
