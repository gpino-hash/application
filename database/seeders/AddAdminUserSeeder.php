<?php

namespace Database\Seeders;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\Address;
use App\Models\Phone;
use App\Models\Picture;
use App\Models\User;
use App\Models\UserInformation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            "email" => "gpino@gmail.com",
            "status" => Status::ACTIVE,
            "type" => UserType::ADMIN,
        ]);
        $user->createToken("administrator", ['*']);
        Address::factory(3)->create([
            "addressable_id" => $user->uuid,
            "addressable_type" => UserInformation::class,
        ]);
        Phone::factory(5)->create([
            "phoneable_id" => $user->uuid,
            "phoneable_type" => UserInformation::class,
        ]);
        Picture::factory()->create([
            "pictureable_id" => $user->uuid,
            "pictureable_type" => UserInformation::class,
        ]);
    }
}
