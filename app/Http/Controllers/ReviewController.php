<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Assessment;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
       //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        $userId = Auth::id();
        $assessment = Assessment::findOrFail($request->assessment_id);

        // get the amount of reviews the current user has submitted
        $userReviewCount = Review::where('assessment_id', $request->assessment_id)
                                ->where('reviewer_id', $userId)
                                ->count();


        //validation to ensure the reviewer only can review a student once
        $request->validate([
            'reviewee_id' => [
                'required',                    // checks the current reviewee id
                Rule::unique('reviews')                 // applies a unique restraint to the entry in the reviews table
                    ->where(function ($query) use ($request, $userId) {                    
                    return $query->where('reviewer_id', $userId)                    // where the the entry contains the reviewer id of the user
                                ->where('assessment_id', $request->assessment_id);      // and the review is made for this assignment
                })
            ],
            'review_text' => ['required', function ($attribute, $value, $fail) {  // word count check
                if (str_word_count($value) < 5) {
                    $fail('The :attribute must be at least 5 words');
                }
            }]
        ], [
            'reviewee_id.unique' => 'You have already reviewed this user.',  // return errors
            'review_text.required' => 'The review text is required.',
        ]);


        // inform the reviewer if they've already met the requirements

        if ($userReviewCount >= $assessment->review_req) {
            return redirect()->route('assessments.show', $request->assessment_id)
            ->withErrors('Required review already amount met.');
        }

        // create the review
        $review = new Review;
        $review->reviewer_id = Auth::id();
        $review->reviewee_id = $request->reviewee_id;
        $review->assessment_id = $request->assessment_id;
        $review->review_text = $request->review_text;
        $review->submitted_at = now();
        $review->save();

        return redirect()->route('assessments.show', $request->assessment_id)->with('success', 'Review submitted.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
