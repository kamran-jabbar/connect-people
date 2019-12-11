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
                'reference' => 'business',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'meeting_name' => 'Friends Meeting',
                'reference' => 'friends',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            )
        ,
            array(
                'meeting_name' => 'School Meeting',
                'reference' => 'school',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'meeting_name' => 'Research Meeting',
                'reference' => 'research',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'meeting_name' => 'Party',
                'reference' => 'party',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
            array(
                'meeting_name' => 'General Meeting',
                'reference' => 'general',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ),
        ));
    }
}
