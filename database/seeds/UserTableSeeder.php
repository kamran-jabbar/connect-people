<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

/**
 * Class UserTableSeeder
 */
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(array(
            array(
                'name' => 'Kamran',
                'email' => 'kamran@connectpeople.com',
                'password' => Hash::make('123456'),
                'latitude' => 62.591705,
                'longitude' => 29.767226,
                'location' => 'Mäntyläntie, 80220 Joensuu',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'name' => 'Mohsin',
                'email' => 'mohsin@connectpeople.com',
                'password' => Hash::make('123456'),
                'latitude' => 62.597365,
                'longitude' => 29.73884,
                'location' => 'Linnunlahdentie 1, 80110 Joensuu, Finland',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'name' => 'Ali',
                'email' => 'ali@connectpeople.com',
                'password' => Hash::make('123456'),
                'latitude' => 62.618273,
                'longitude' => 29.74372,
                'location' => 'Kuurnankatu 32, 80130 Joensuu, Finland',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            )
        ));
    }
}
