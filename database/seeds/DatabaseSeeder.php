<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CityTableSeeder::class);
        $this->call(BrandSeeder::class);
        $this->call(CarTypeSeeder::class);
        $this->call(FeaturesSeeder::class);
        $this->call(LocationsSeeder::class);
        $this->call(SuperAdminSeeder::class);
    }
}
