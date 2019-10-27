<?php

use Illuminate\Database\Seeder;


class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('cities')->insert([
        'name' => 'الرياض',
        'is_active' => 1,
      ]);
      DB::table('cities')->insert([
        'name' => 'جدة',
        'is_active' => 1,
      ]);
      DB::table('cities')->insert([
        'name' => 'مكة المكرمة',
        'is_active' => 1,
      ]);
      DB::table('cities')->insert([
        'name' => 'المدينة المنورة',
        'is_active' => 1,
      ]);
      DB::table('cities')->insert([
        'name' => 'تبوك',
        'is_active' => 1,
      ]);
    }
}
