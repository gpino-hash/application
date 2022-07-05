<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Phone;
use App\Models\Picture;
use App\Models\UserInformation;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CountrySeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(SiteSeeder::class);
        $this->call(AddAdminUserSeeder::class);
        $this->call(ProductSeeder::class);

        UserInformation::factory(50)->create()->each(function ($user) {
            Address::factory(3)->create(["addressable_id" => $user->uuid, "addressable_type" => UserInformation::class,]);
            Phone::factory(5)->create(["phoneable_id" => $user->uuid, "phoneable_type" => UserInformation::class,]);
            Picture::factory()->create(["pictureable_id" => $user->uuid, "pictureable_type" => UserInformation::class,]);
        });
    }
}
