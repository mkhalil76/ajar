<?php

use Illuminate\Database\Seeder;


class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('brands.json');

        $json = json_decode(file_get_contents($path), true);

        if(!empty($json)){
            foreach($json as $key => $value){
                $insert[] = ['name' => $value['name']];
            }

            if(!empty($insert)){
                DB::table('brands')->insert($insert);
            }
        }
    }
}
