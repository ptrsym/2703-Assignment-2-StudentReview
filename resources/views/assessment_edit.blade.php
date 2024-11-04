@extends('layouts.master')

@section('title')
    Edit Assessment
@endsection

@section('content')
<div class="container">

    <h1>Assessment Edit</h1>

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

    <div class="assessment-form">
        <form class="assessment-form" method="POST" action="{{ route('assessments.update', $assessment->id) }}">
            @csrf
            @method('PUT')
            <p><label for="assessment_title">Title:</label></p>
            <input type="text" name="assessment_title" required value="{{ old('assessment_title', $assessment->title) }}">

            <p><label for="assessment_instructions">Instructions:</label></p>
            <textarea rows="4" cols="30" name="assessment_instruction" required>{{ old('assessment_instruction', $assessment->instruction) }}</textarea>

            <p><label for="assessment_review_req">Review Requirement:</label></p>
            <input type="text" name="assessment_review_req" required value="{{ old('assessment_review_req', $assessment->review_req) }}">

            <p><label for="assessment_score">Score:</label></p>
            <input type="text" name="assessment_score" required value="{{ old('assessment_score', $assessment->score) }}">

            <p><label for="assessment_due_date">Due Date:</label></p>
            <input type="datetime-local" name="assessment_due_date" required value="{{ old('assessment_due_date', $assessment->due_date) }}">

            <p><label for="assessment_type">Type:</label></p>
            <select name="assessment_type" required>
                <option value="student-select" {{old('assessment_type')}} == 'student-select' ? 'selected' : ''>Student Select</option>
                <option value="teacher-assign" {{old('assessment_type')}} == 'teacher-assign' ? 'selected' : ''>Teacher Assign</option>
            </select>

            <p><label for="assessment_group_size">Group Size (set 1 for Student Select):</label></p>
            <input type="text" name="assessment_group_size" required value="{{ old('assessment_group_size', $assessment->group_size) }}">

            <input type="hidden" name="course_id" value="{{$assessment->course_id}}">
            <p><button class="submit-btn" type="submit">Update</button></p>
        </form>
        <form method="POST" action="{{route('assessments.destroy', $assessment->id)}}">
            @csrf
            @method('DELETE')
            <button class="delete-btn" type="submit">Delete</button>
        </form>

    </div>
</div>
@endsection