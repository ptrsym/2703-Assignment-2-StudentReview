<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\Review;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
    public function store(Request $request)
    {
        $course_id = $request->input('course_id');

            //count all the students in the course
            $students = User::whereHas('courses', function($query) use ($course_id) {
                    $query->where('course_id', $course_id);
            })->where('role', 'student')->count();


            //validate request
        $validate = $request->validate([
            "assessment_title" => 'required|unique:assessments,title|max:20',
            "assessment_instruction" => 'required|max:255',
            "assessment_review_req" => 'required|numeric|min:1',
            "assessment_score" => 'required|numeric|min:1|max:100',
            "assessment_due_date" => 'required|date|after:now' ,
            "assessment_type" => 'required',
            //ensures inappropriate group size cant be selected
            "assessment_group_size" => 'required|integer|min:1|max:'.$students
        ]);

        //add the assignment
        $assessment = new Assessment;
        $assessment->title = $request->assessment_title;
        $assessment->instruction = $request->assessment_instruction;
        $assessment->review_req =$request->assessment_review_req;
        $assessment->score = $request->assessment_score;
        $assessment->due_date = $request->assessment_due_date;
        $assessment->type = $request->assessment_type;
        $assessment->course_id = $request->course_id;
        $assessment->group_size = $request->assessment_group_size;
        $assessment->save();

        // group the students based on the type
        if ($assessment->type === 'student-select') {
            $this->createStudentSelectGroup($request->course_id, $assessment);
        } elseif ($assessment->type === 'teacher-assign') {
            $this->createTeacherAssignGroup($request->course_id, $assessment, $request->assessment_group_size);
        }

        return redirect()->route('courses.show', $request->course_id)
                        ->with('success', 'Assessment created successfully and groups assigned.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        //eager load users with the assessment for use in view
        $assessment = Assessment::with('users')->findOrFail($id);


        $authUserId = Auth::id();

        //for displaying reviewees to the currently logged in student
        $group = Group::where('assessment_id', $id)
                        ->whereHas('users', function($query) use ($authUserId) {
                            $query->where('users.id', $authUserId);
                        })->first();

        
        //handles a case if there are no users in the group for testing              
        $reviewees = [];

        if ($group) {
            $reviewees = $group->users()
                    ->where('users.id', '!=', $authUserId)
                    ->get(); 
            }

            // retrieve the reviews submitted by the logged in user
        $submissions = Review::where('assessment_id', $id)
                                ->where('reviewer_id',  $authUserId)
                                ->get();

            //retrieve the reviews submitted to the logged in user                 
        $received_reviews = Review::where('assessment_id', $id)
                                ->where('reviewee_id', $authUserId)
                                ->get();

            // retrieve all the students through the assessments relationship with the current assessment and paginate the results
        $students = User::whereHas('assessments', function ($query) use ($id) {
            $query->where('assessment_id', $id);
        })->paginate(10);

        
            // return the view with all the data
        return view('assessment_detail')->with('assessment', $assessment)
                                    ->with('reviewees', $reviewees)
                                    ->with('submissions', $submissions)
                                    ->with('received_reviews', $received_reviews)
                                    ->with('students', $students);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {   
        // finds any submissions associated with the assessment for validation
        $hasSubmission = Review::where('assessment_id', $id)->exists();

        // gets the current assessment
        $assessment = Assessment::findOrFail($id);

        // deny editing if a submission has already been made
        if ($hasSubmission) {
            return redirect()->route('courses.show', $assessment->course_id)
                ->with('error', 'Cannot edit assessment as submissions have already been made.');     
        }

        return view('assessment_edit')
                ->with('assessment', $assessment);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $course_id = $request->input('course_id');

        //count all the students in the course
        $students = User::whereHas('courses', function($query) use ($course_id) {
                $query->where('course_id', $course_id);
        })->where('role', 'student')->count();

        //validation logic for creating the assessment
    $validate = $request->validate([
        "assessment_title" => 'required|unique:assessments,title,'.$id.'|max:20',
        "assessment_instruction" => 'required|max:255',
        "assessment_review_req" => 'required|numeric|min:1',
        "assessment_score" => 'required|numeric|min:1|max:100',
        "assessment_due_date" => 'required|date|after:now' ,
        "assessment_type" => 'required',
        "assessment_group_size" => [
            'required',
            'integer',
            'min:1',
            // set a fail condition if trying to allocate a group size greater than the pool of students 
            function($attribute, $value, $fail) use ($students) {
                if ($students > 0 && $value > $students) {
                    $fail('The group of size cannot be greater than the number of students.');
                }
            }
        ]
    ]);

    //make the item and save
    $assessment = Assessment::findOrFail($id);
    $assessment->title = $request->assessment_title;
    $assessment->instruction = $request->assessment_instruction;
    $assessment->review_req =$request->assessment_review_req;
    $assessment->score = $request->assessment_score;
    $assessment->due_date = $request->assessment_due_date;
    $assessment->type = $request->assessment_type;
    $assessment->group_size = $request->assessment_group_size;
    $assessment->save();

    // apply the group sorting logic
    if ($assessment->type === 'student-select') {
        $this->createStudentSelectGroup($request->course_id, $assessment);
    } elseif ($assessment->type === 'teacher-assign') {
        $this->createTeacherAssignGroup($request->course_id, $assessment, $request->assessment_group_size);
    }

    return redirect()->route('courses.show', $request->course_id)
                ->with('success', $assessment->title.' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        // find the current assessment item
        $assessment = Assessment::findOrFail($id);
        // remember the id passed for redirection
        $course_id = $assessment->course_id;
        $assessment->delete();

        return redirect()->route('courses.show', $course_id );
    }


    // function to create a group for the assignment
    public function createStudentSelectGroup($course_id, $assessment) 
    {   
        //prepare a group for the assessment
        $group = Group::create([
            'assessment_id' => $assessment->id,
        ]);

        //get all the students in the course through the courses relationship in the enrolment table
        $students = User::whereHas('courses', function($query) use ($course_id) {
                $query->where('course_id', $course_id);
        })->where('role', 'student')->get();


        //attach them to the group
        foreach ($students as $student) {
            $group->users()->attach($student->id);

            // Attach user to the assessment_user table with default score
            $assessment->users()->attach($student->id, ['score' => 0]); // default score of 0

        }
    }
    

    //create groups for teacher-assign with an optional group size
    public function createTeacherAssignGroup($course_id, $assessment, $groupSize = 3)
    {
        //get all the students in the course through enrolment table
            $students = User::whereHas('courses', function($query) use ($course_id) {
                $query->where('course_id', $course_id);
        })->where('role', 'student')->get();

        // calculate number of students and the amount of full groups possible
        $totalStudents = $students->count();
        $totalGroups = floor($totalStudents / $groupSize);
        $remainder = $totalStudents % $groupSize;   //save leftovers for adjustment later


        //create the groups

        $createdGroups = []; // keep track of how many groups are made
        for ($i = 1; $i <= $totalGroups; $i++) {
            $group = Group::create([
                'assessment_id' => $assessment->id,
            ]);

            $createdGroups[] = $group; // counter

            $studentsToGroup = $students->splice(0, $groupSize);  //removes students from the pool as they get added to a group 
            foreach ($studentsToGroup as $student) {
                $group->users()->attach($student->id);

                // Attach user to the assessment_user table with default score
                $assessment->users()->attach($student->id, ['score' => 0]); //default score of 0
            }

        }

        //add the remainders to groups
        if ($remainder > 0) {
            if ($remainder <= ($groupSize / 2)) {        // if less than half assigned group size append to existing groups
                $studentsToGroup = $students->splice(0, $remainder);
                foreach ($studentsToGroup as $student) {
                    $randomgroup = $createdGroups[array_rand($createdGroups)];  // randomly assign them
                    $randomgroup->users()->attach($student->id);

                    // Attach user to the assessment_user table with default score
                     $assessment->users()->attach($student->id, ['score' => 0]); // default score of 0
                }
            } else {
                // if there's enough for a new group
                $group = Group::create([
                    'assessment_id' => $assessment->id,
                ]);
                foreach ($students as $student) {
                    $group->users()->attach($student->id);

                    // Attach user to the assessment_user table with default score
                    $assessment->users()->attach($student->id, ['score' => 0]); //  default score of 0
                }
            }
        }
    }


}
