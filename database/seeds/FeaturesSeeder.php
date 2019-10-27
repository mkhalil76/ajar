<?php

use Illuminate\Database\Seeder;

class FeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('features')->insert([
            'name' => 'مقاعد اطفال',
        ]);

        DB::table('features')->insert([
            'name' => 'واي فاي',
        ]);
        DB::table('features')->insert([
            'name' => 'مداخل USB',
        ]);
        DB::table('features')->insert([
            'name' => 'وسائد حماية'
        ]);
    }
}
