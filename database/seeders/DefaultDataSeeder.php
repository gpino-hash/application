<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DefaultDataSeeder extends Seeder
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
    }
}
