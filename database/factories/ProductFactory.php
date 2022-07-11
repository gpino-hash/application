<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Currency;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $site = Site::query()->select("uuid")->first();
        $currency = Currency::query()->inRandomOrder()->select("uuid")->first();
        return [
            "title" => $this->faker->title,
            "description" => $this->faker->sentence,
            "stock" => $this->faker->randomDigit(),
            "price" => rand(12, 57) / 10,
            "status" => $this->faker->randomElement(Status::values()),
            "site_uuid" => $site->uuid,
            "currency_uuid" => $currency->uuid,
        ];
    }
}
