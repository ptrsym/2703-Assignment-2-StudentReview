<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Review;
use App\Models\Assessment;
use App\Models\User;
use App\Models\Group;

class ReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $assessments = Assessment::with('groups')->get();

        foreach ($assessments as $assessment) {
            $groups = $assessment->groups;

            foreach ($groups as $group) {
                $students = $group->users()->where('role', 'student')->get();


                foreach ($students as $reviewer) {

                    //get the numbers of times to review
                    $reviewcount = $assessment->review_req;
                    //randomise the students and exclude the reviewer
                    $reviewees = collect($students)->where('id', '!=', $reviewer->id)->shuffle();
                    //take only the required number of reviewees
                    if ($reviewees->count() > $reviewcount) {
                        $reviewees = $reviewees->take($reviewcount);
                    }

                    foreach ($reviewees as $reviewee) {
                        DB::table('reviews')->insert([
                            'review_text' => 'This is some placeholder review text.',
                            'submitted_at' => now(),
                            'assessment_id' => $assessment->id,
                            'reviewer_id' => $reviewer->id,
                            'reviewee_id' => $reviewee->id,
                        ]);
                    }
                }
            }
        }
    }
         
}

