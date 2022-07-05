<?php

namespace Database\Factories;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Phone>
 */
class PhoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "phone" => $this->faker->phoneNumber,
            "type" => $this->faker->randomElement(["residential", "personal"]),
            "operator" => $this->faker->word,
            "status" => $this->faker->randomElement([Status::ACTIVE, Status::INACTIVE]),
        ];
    }
}
