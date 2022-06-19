<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Models\Address;
use App\Models\Phone;
use App\Models\Picture;
use App\Models\User;
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
        $this->call(CurrencySeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(SiteSeeder::class);

        $user = User::factory()->create([
            "email" => "gpino@gmail.com",
            "status" => Status::ACTIVE,
        ]);
        $user->createToken("administrator", ['*']);
        Address::factory(3)->create(["addressable_id" => $user->uuid,]);
        Phone::factory(5)->create(["phoneable_id" => $user->uuid,]);
        Picture::factory()->create(["pictureable_id" => $user->uuid,]);
        UserInformation::factory(100)->create()->each(function ($user) {
            Address::factory(3)->create(["addressable_id" => $user->uuid,]);
            Phone::factory(5)->create(["phoneable_id" => $user->uuid,]);
            Picture::factory()->create(["pictureable_id" => $user->uuid,]);
        });
    }
}
