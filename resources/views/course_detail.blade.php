@extends ('layouts.master')

@section ('title')
    Course Details
@endsection

@section ('content')
<div class="container">
<div class="details-container">

    <div class="details">
        
        <h1>{{ $course->course_name }}</h1> 
        <h2>Teaching Staff</h2>
        <ul>
            @foreach ($teachers as $teacher)
            <li>{{$teacher->name}}</li>
            @endforeach    
        </ul>
    </div>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
</div>
<div class="course-content">
    
       
    <div class="students">
        @if (auth()->user()->role == 'teacher')
        <h2>Students</h2>
        <form class="enrolment-form" method="POST" action="{{ url('enrolments') }}">
            @csrf
            <p><label for="snumber">Enter Snumber to Enroll:</label></p>
            <input type="text" name="snumber" value="{{ old('snumber') }}">
            <input type="hidden" name="course_id" value="{{$course->id}}">
            <p><button class="submit-btn" type="submit" value="store">Enroll Student</button></p>
        </form>    
        <ul>
            @foreach ($students as $student)
            <li>{{$student->name}}</li>
            @endforeach
        </ul>
        {{$students->links()}}
        @endif
    </div>
    
    @if (auth()->user()->role == 'teacher')
    <div class="assessment-form">
        <h2>Create Assessment</h2>
        <form class="assessment-form" method="POST" action="{{ route('assessments.store') }}">
            @csrf
            <p><label for="assessment_title">Title:</label></p>
            <input type="text" name="assessment_title" required value="{{ old('assessment_title') }}">
            <p><label for="assessment_instructions">Instructions:</label></p>
            <textarea rows="4" cols="30" name="assessment_instruction" required>{{ old('assessment_instruction') }}</textarea>
            <p><label for="assessment_review_req">Review Requirement:</label></p>
            <input type="text" name="assessment_review_req" required value="{{ old('assessment_review_req') }}">
            <p><label for="assessment_score">Score:</label></p>
            <input type="text" name="assessment_score" required value="{{ old('assessment_score') }}">
            <p><label for="assessment_due_date">Due Date:</label></p>
            <input type="datetime-local" name="assessment_due_date" required value="{{ old('assessment_due_date') }}">
            <p><label for="assessment_type">Type:</label></p>
            <select name="assessment_type" required>
                <option value="student-select" {{old('assessment_type')}} == 'student-select' ? 'selected' : ''>Student Select</option>
                <option value="teacher-assign" {{old('assessment_type')}} == 'teacher-assign' ? 'selected' : ''>Teacher Assign</option>
            </select>
            <p><label for="assessment_group_size">Group Size (set 1 for Student Select):</label></p>
            <input type="text" name="assessment_group_size" required value="{{ old('assessment_group_size') }}">
            <input type="hidden" name="course_id" value="{{$course->id}}">
            <p><button class="submit-btn" type="submit">Create</button></p>
        </form>
        @endif
</div>    

<div class="assessment">
    <h2>Assessments</h2>
        <ul>
            @foreach ($assessments as $assessment)
            <li>
                <a href='{{route("assessments.show", $assessment->id)}}'>{{$assessment->title }}</a> Due: {{$assessment->due_date}}
                 @if (auth()->user()->role == 'teacher')
                    <a href="{{route('assessments.edit', $assessment->id)}}"><button class="edit-btn">Edit</button></a>
                @endif
             </li>
            @endforeach
        </ul>
    </div>
</div>
    
    
        

</div>




@endsection


