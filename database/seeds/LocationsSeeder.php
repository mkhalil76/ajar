<?php

use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert([
            'name' => 'حارة المظلوم',
            'city_id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'سور جدة',
            'city_id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'منطقة البلد التاريخيه',
            'city_id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'بيت نصيف',
            'city_id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'شاطئ الرمال',
            'city_id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'ملعب الجوهرة',
            'city_id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'باب مكة',
            'city_id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'جزيرة الشراع',
            'city_id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'المسجد العائم',
            'city_id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'نافورة الملك',
            'city_id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'واحة جدة',
            'city_id' => 2
        ]);
        DB::table('locations')->insert([
            'name' => 'الدلم',
            'city_id' => 1
        ]);
        DB::table('locations')->insert([
            'name' => 'البجادية',
            'city_id' => 1
        ]);
        DB::table('locations')->insert([
            'name' => 'الجريفة',
            'city_id' => 1
        ]);
        DB::table('locations')->insert([
            'name' => 'الحريق (الرياض)',
            'city_id' => 1
        ]);
        DB::table('locations')->insert([
            'name' => 'الحفيرة',
            'city_id' => 1
        ]);
        DB::table('locations')->insert([
            'name' => 'الداهنة',
            'city_id' => 1
        ]);
        DB::table('locations')->insert([
            'name' => 'الدرعية',
            'city_id' => 1
        ]);
        DB::table('locations')->insert([
            'name' => 'الزلفي',
            'city_id' => 1
        ]);
        DB::table('locations')->insert([
            'name' => 'السليل',
            'city_id' => 1
        ]);
        DB::table('locations')->insert([
            'name' => 'السيح',
            'city_id' => 1
        ]);
        DB::table('locations')->insert([
            'name' => 'العيينة',
            'city_id' => 1
        ]);
    }
}
