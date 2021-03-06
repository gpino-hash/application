<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "country_uuid" => Country::factory(),
            "state" => $this->faker->city,
            "city" => $this->faker->city,
            "address" => $this->faker->streetAddress(),
            "type" => $this->faker->randomElement(["house", "work", "other"]),
            "postal_code" => $this->faker->randomDigit(),
            "status" => $this->faker->randomElement([Status::ACTIVE, Status::INACTIVE,]),
        ];
    }
}
