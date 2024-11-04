<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {  
        $courses = [
            ['course_code' => 'ICT101', 'course_name' => 'Intro to Programming'],
            ['course_code' => 'ICT102', 'course_name' => 'Data Structures'],
            ['course_code' => 'ICT103', 'course_name' => 'Web Development'],
            ['course_code' => 'ICT104', 'course_name' => 'Database Systems'],
            ['course_code' => 'ICT105', 'course_name' => 'Software Engineering'],
        ];

        foreach ($courses as $course) {
            DB::table('courses')->insert($course);
        }
    }
}
