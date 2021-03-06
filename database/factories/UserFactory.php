<?php

namespace Database\Factories;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'email_verified_at' => $this->faker->randomElement([null, now()]),
            'password' => 'password', // password
            'remember_token' => Str::random(10),
            'status' => $this->faker->randomElement(Status::values()),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function active(): UserFactory
    {
        return $this->state(["status" => Status::ACTIVE,]);
    }

    public function inactive(): UserFactory
    {
        return $this->state(["status" => Status::INACTIVE,]);
    }

    public function locked(): UserFactory
    {
        return $this->state(["status" => Status::LOCKED,]);
    }

    public function slow(): UserFactory
    {
        return $this->state(["status" => Status::SLOW,]);
    }
}
