<?php

namespace Database\Seeders;

use App\Models\Picture;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory(50)->create()->each(function ($product) {
            Picture::factory(5)->create([
                "pictureable_id" => $product->uuid,
                "pictureable_type" => Product::class,
            ]);
        });
    }
}
