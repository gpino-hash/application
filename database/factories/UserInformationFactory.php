<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserInformation>
 */
class UserInformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "id_number" => $this->faker->numberBetween(1, 500000),
            "firstname" => $this->faker->firstName,
            "lastname" => $this->faker->lastName,
            "birthdate" => $this->faker->date,
            "user_uuid" => User::factory(),
        ];
    }
}
