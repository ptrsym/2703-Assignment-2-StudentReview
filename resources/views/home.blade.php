@extends('layouts.master')

@section('title')
    Home
@endsection

@section('content')

<h1>Your Courses</h1>

<ul>
    @foreach ($courses as $course)
    <a href="{{route('courses.show', $course->id)}}"><li>{{ $course->course_name }}</li></a>
    @endforeach
</ul>

@endsection