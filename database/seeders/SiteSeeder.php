<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currency = Currency::query()->select("uuid")->first();
        Site::query()->create([
            "name" => "major",
            "branch_office" => "main site",
            "symbol" => "MSVE",
            "currency_uuid" => $currency->uuid,
        ]);
    }
}
