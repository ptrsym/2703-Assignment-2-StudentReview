@extends('layouts.master')

@section('title')
    Student Review
@endsection
<div class="container">

    @section('content')

    @if (auth()->user()->role == 'teacher')
    
<h1>Reviews for {{ $student->name }} </h1>
   <h2>Assessment: {{ $assessment->title }}</h2> 
   <h4>Current Grade: {{$student->assessments()->where('assessment_id', $assessment->id)->first()->pivot->score}}</h4>

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

<form method="POST" action="{{ route('student_review.mark', ['assessment' => $assessment->id, 'student' => $student->id]) }}">
    @csrf
    <p><label for="score">Grade Student out of {{$assessment->score}}:</label></p>
    <input type="text" name="score" value="{{$student->assessments()->where('assessment_id', $assessment->id)->first()->pivot->score}}">
    <button action="submit">Grade Student</button>
</form>
<div class="content">
    <div class="submitted-reviews">
        <h2>Submitted Reviews</h2>
        <ul>
            @foreach($submittedReviews as $review)
            <li>Reviewed: {{ $review->reviewee->name }} <br>
                Review: {{ $review->review_text }}
            </li> 
            @endforeach
        </ul>
    </div>
    
    <div class="received-reviews">
        <h2>Received Reviews</h2>
        <ul>
            @foreach($receivedReviews as $review)
            <li>Reviewer: {{ $review->reviewer->name }} <br>
                Review: {{ $review->review_text }}
            </li> 
            @endforeach
        </ul>
    </div>
</div>
    
    @else
    <div class="alert alert-danger">
        <h1>Restricted area.</h1>
    </div>
    @endif  
</div>
@endsection