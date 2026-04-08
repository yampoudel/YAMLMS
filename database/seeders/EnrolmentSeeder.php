<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnrolmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Insert enrolment data into table lms_enrolments
        DB::table('lms_enrolments')->insert([ 
            [
            'user_id' => 5,
            'course_id' => 3,
            'enrolled_at' => now(),
            'enrolled_by' => 2,
            'created_at' => now(),
            'updated_at' => now(),
            ],           
            [
            'user_id' => 4,
            'course_id' => 5,
            'enrolled_at' => now(),
            'enrolled_by' => 2,
            'created_at' => now(),
            'updated_at' => now(),
            ],
            [
            'user_id' => 7,
            'course_id' => 4,
            'enrolled_at' => now(),
            'enrolled_by' => 2,
            'created_at' => now(),
            'updated_at' => now(),
            ],
        ]);
    }
}
