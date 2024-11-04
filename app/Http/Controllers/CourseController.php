<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Group;

class CourseController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)

    {
        //eager load assessments for the course
        $course = Course::with(['assessments'])->findOrFail($id);
        //separate students and teachers for display
        $teachers = $course->users()->where('role', 'teacher')->get();
        $students = $course->users()->where('role', 'student')->paginate(10);

        //get the groups for the course through the assessment table
        $groups = Group::whereHas('assessment', function ($query) use ($id) {
                $query->where('course_id', $id);
        })->get();   
 
        return view('course_detail', [
             'course' => $course,
             'assessments' => $course->assessments,
             'teachers' => $teachers,
             'students' => $students,
             'groups' => $groups,
        ]);
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
