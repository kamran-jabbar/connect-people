<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

/**
 * Class MeetingTypeTableSeeder
 */
class MeetingTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('meeting_type')->insert(array(
            array(
                'meeting_name' => 'Business Meeting',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'meeting_name' => 'Friends Meeting',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            )
        ,
            array(
                'meeting_name' => 'School Meeting',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'meeting_name' => 'Research Meeting',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'meeting_name' => 'Party',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'meeting_name' => 'General Meeting',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),

        ));
    }
}
