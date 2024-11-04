<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\User;
use App\Models\Review;

class StudentReviewController extends Controller
{
    //display the page for the teacher to view all the students reviews and mark them
    public function show($assessment_id, string $id)
    {
        $assessment = Assessment::findOrFail($assessment_id);
        $student = User::findOrFail($id);
        $submittedReviews = $student->givenReviews()->where('assessment_id', $assessment_id)->get();
        $receivedReviews = $student->receivedReviews()->where('assessment_id', $assessment_id)->get();

        return view('student_review', compact('student', 'submittedReviews', 'receivedReviews', 'assessment'));

    }

    // handles the marking page form request and assigns them a score
    public function mark($assessment_id, $student_id, Request $request)

    {
        $maxScore = Assessment::findOrFail($assessment_id)->score;
        $givenScore = $request->score;

        $request->validate([
            'score' => 'required|numeric|max:'.$maxScore,       //ensure the given mark cant be higher than the max set for the assignment
        ]);

        $student = User::findOrFail($student_id);        // get the student
       
        $assessment = $student->assessments()->where('assessment_id', $assessment_id)->first();

        $assessment->pivot->score = $givenScore;   // update the make for the correct assessment
        $assessment->pivot->save();

    return redirect()->back()->with('success', 'Student marked');

   }
}