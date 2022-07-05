<?php

namespace Database\Factories;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Picture>
 */
class PictureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "title" => $this->faker->word,
            "description" => $this->faker->sentence(),
            "url" => $this->faker->imageUrl(),
            "status" => $this->faker->randomElement([Status::ACTIVE, Status::INACTIVE]),
        ];
    }
}
