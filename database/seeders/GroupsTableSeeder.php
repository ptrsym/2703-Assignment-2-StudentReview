<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\User;
use App\Models\Assessment;
use App\Models\Group;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //complicated seeder but was practice for the main code logic

        $courses = Course::all();

        foreach ($courses as $course) {
            // Get all students enrolled in this course
            $students = User::where('role', 'student')
                            ->whereHas('courses', function($query) use ($course) {
                                $query->where('course_id', $course->id);
                            })->get();

            // get all assignments for the course
            $assessments = Assessment::where('course_id', $course->id)
                        ->where('type', 'student-select')
                        ->get();

            // for each assignment in the course create a group
            foreach ($assessments as $assessment) {
                $group = Group::create([
                    'group_type' => 'student-select',
                    'assessment_id' => $assessment->id,
                ]);

                //for each group in the course attach all the students in the course to that group
                foreach ($students as $student) {
                    $group->users()->attach($student->id);
                }
            }

        }
    }
}
