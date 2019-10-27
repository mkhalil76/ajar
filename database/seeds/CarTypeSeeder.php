<?php

use Illuminate\Database\Seeder;

class CarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('car_types.json');

        $json = json_decode(file_get_contents($path), true);

        if(!empty($json)){
            foreach($json as $key => $value){
                $brand = $value['brand'];
                $brand_id = DB::table('brands')->where('name', '=', $brand)->first();

                $models = ['2019', '2018', '2017','2016', '2015'];
                $k = array_rand($models);
                $v = $models[$k];

                if (!empty($brand_id)) {
                    foreach ($value['models'] as $model) {
                        $insert[] = ['name' => $model, 'brand_id' => $brand_id->id, 'model' => $v, 'picture' => 'https://cars.usnews.com/static/images/Auto/izmo/i2313698/2015_bmw_4_series_angularfront.jpg'];
                    }
                }
            }
            if(!empty($insert)){
                DB::table('car_types')->insert($insert);
            }
        }
    }
}
