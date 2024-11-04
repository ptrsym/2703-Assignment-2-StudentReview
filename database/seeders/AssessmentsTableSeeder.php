<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   

        $assessments = [
            [
                'title' => 'Workshop 1',
                'instruction' => 'Complete the first assignment by following the instructions.',
                'review_req' => 3, // Number of reviews required
                'score' => 100,
                'due_date' => now()->addDays(7), // Due in 7 days
                'type' => 'student-select',
                'course_id' => 1, // Randomly assign a course
            ],
            [
                'title' => 'Assignment 1',
                'instruction' => 'Prepare for the midterm exam.',
                'review_req' => 3,
                'score' => 100,
                'due_date' => now()->addDays(14), // Due in 14 days
                'type' => 'student-select',
                'course_id' => 1,
            ],
            [
                'title' => 'Workshop 2',
                'instruction' => 'Collaborate with your group to complete the project.',
                'review_req' => 4,
                'score' => 100,
                'due_date' => now()->addDays(21),
                'type' => 'student-select',
                'course_id' => 2,
            ],
            [
                'title' => 'Workshop 3',
                'instruction' => 'Prepare for the final exam.',
                'review_req' => 6,
                'score' => 100,
                'due_date' => now()->addDays(30),
                'type' => 'student-select',
                'course_id' => 3,
            ],
            [
                'title' => 'Reflection Paper',
                'instruction' => 'Write a reflection on what you learned this semester.',
                'review_req' => 2,
                'score' => 100,
                'due_date' => now()->addDays(10),
                'type' => 'student-select',
                'course_id' => 4,
            ],
        ];

        foreach ($assessments as $assessment) {
            DB::table('assessments')->insert($assessment);
        }
    }
}
