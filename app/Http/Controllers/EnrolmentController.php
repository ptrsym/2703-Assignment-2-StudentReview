<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Enrolment;
use App\Models\Course;

class EnrolmentController extends Controller
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

        // check if snumber exists
        $validate = $request->validate([
            'snumber' => 'required | exists:users,snumber',
        ]);

        //retrive the associated user
        $user = User::where('snumber', $request->input('snumber'))->first();

        //check if the user is a student and decline if not
        if ($user->role != 'student') {
            return redirect()->route('courses.show', $request->input('course_id'))->withErrors(['snumber' => "The provided snumber does not belong to a student"]);
        }

        $course = Course::findOrFail($request->input('course_id'));

        //look in the users relationship (enrolment table) for the user if they're already enrolled in this course
        if ($course->users()->where('users.id', $user->id)->exists()) {
            //exit if found
            return redirect()->route('courses.show', $request->input('course_id'))
                            ->withErrors(['snumber' => 'This student is already enrolled in ' .$course->course_name]);
        }

        // make the enrolment
        $enrolment = new Enrolment();
        $enrolment->user_id = $user->id;
        $enrolment->course_id = $request->input('course_id');
        $enrolment->save();

        return redirect()->route('courses.show', $request->input('course_id'))->with('success', $user->name . ' enrolled.');
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
