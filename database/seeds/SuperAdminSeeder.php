<?php

use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('providers')->insert([
            'office_name' => 'Super Admin',
            'owner_name' => 'Super Admin',
            'commercial_no' => '111111111',
            'admin_name' => 'Admin',
            'admin_mobile' => '1111111111',
            'password' => bcrypt('1111111111'),
            'super_admin' => 1,
        ]);
    }
}
