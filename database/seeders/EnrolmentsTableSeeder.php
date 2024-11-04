<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnrolmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $courseids = [1, 2, 3 ,4 ,5];

        for ($i = 1; $i <= 5; $i++) {
            DB::table('enrolments')->insert([
                'user_id' => $i, // teachers are users 1 to 5
                'course_id' => $courseids[$i - 1],
            ]);
        }
        // Enrol Students
        for ($i = 6; $i <= 55; $i++) { // Students start from user_id 6

            //pick 3 random courses for each student ensuring no duplicates
            $shuffledCourses = $courseids;
            shuffle($shuffledCourses);
            $randomCourses = array_slice($shuffledCourses, 0, 3);

            foreach ($randomCourses as $courseId) {
                DB::table('enrolments')->insert([
                    'user_id' => $i, // Student ID
                    'course_id' => $courseId, // Enroll in random course
                ]);
            }
        }
    }
}