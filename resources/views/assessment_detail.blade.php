@extends ('layouts.master')

@section ('title')
    Assessment {{$assessment->title}} Details
@endsection

@section ('content')
<div class="container">
<div class="student_review--wrapper">

    @if (auth()->user()->role=='student')
    <div class="assessment_details--student">
        <h1>{{$assessment->title}}</h1>
        <h2>Instructions:</h2>
        <p>{{$assessment->instructions}}</p>
        <h3>Due:</h3>
        <p>{{$assessment->due_date}}</p>
        <h3>Required Reviews:</h3>
        <p>{{$assessment->review_req}}</p>
        <h4>Review Type:</h4>
        <p>{{$assessment->type}}</p>

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
        <h4>Submit a Review:</h4>
        <form method="POST" action="{{route('reviews.store')}}">
            @csrf
            <select name="reviewee_id" id="reviewee_id">
                @foreach ($reviewees as $reviewee)
                <option value="{{$reviewee->id}}">{{$reviewee->name}}</option>
                @endforeach
            </select>
            <p><textarea name="review_text" id="review_text" cols="30" rows="5"></textarea></p>
            <p><button class="submit-btn" type="submit">Submit Review</button></p>
            <input type="hidden" name="assessment_id" value="{{$assessment->id}}">
        </form>
    </div>

    <div class="your-submissions">
        <h4>Your Submissions:</h4>
        <ul>
            @foreach ($submissions as $submission)
            <div class="submission-display">
                <li>For: {{$submission->reviewee->name}}</li>
                <li>Submitted at: {{$submission->submitted_at}}</li>
                <li>{{$submission->review_text}}</li>
            </div>
            @endforeach
        </ul>
    </div>

    <div class="reviews-received">
        <h4>Reviews Recevied</h4>
        <ul>
            @foreach ($received_reviews as $received_review)
            <div class="reviews-display">
                <li>Reviewed by: {{$received_review->reviewer->name}}</li>
                <li>{{$received_review->review_text}}</li>
            </div>
            @endforeach
        </ul>
    </div>
</div>
    @endif
    

    @if(auth()->user()->role=='teacher')
        <div class="assessment_details--teacher">
            <h1>{{$assessment->title}}</h1>
            <h4>Required Reviews: {{$assessment->review_req}}</h4>
            <div class="student-stats">
                <ul class="student-stats--list">
                    @foreach ($students as $student)
                    <li> 
                    Name: <a href="{{ route('student_review', ['assessment' => $assessment->id, 'student' => $student->id]) }}">{{$student->name}}</a><br>
                    Reviews Submitted: {{ $student->givenReviews->where('assessment_id', $assessment->id)->count() }} <br>
                    Reviews Received: {{ $student->receivedReviews->where('assessment_id', $assessment->id)->count() }} <br>
                    Assessment Score: {{ $student->assessments()->where('assessment_id', $assessment->id)->first()->pivot->score }} <br>
                </li>
                @endforeach
            </ul>
        </div>
        {{$students->links()}}
        </div>
     @endif
    
</div>
    @endsection