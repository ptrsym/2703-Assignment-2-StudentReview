<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\EnrolmentController;
use App\Http\Controllers\StudentReviewController;

use Illuminate\Support\Facades\Route;


//apply middleware to all routes redirecting if not logged in
Route::middleware('auth')->group(function () {

Route::get('/home', [UserController::class, 'index'])->name('home');
Route::get('student_review/{assessment}/{student}', [StudentReviewController::class, 'show'])->name('student_review');
Route::post('student_review/{assessment}/{student}/mark', [StudentReviewController::class, 'mark'])->name('student_review.mark');
Route::resource('enrolments', EnrolmentController::class);
Route::resource('assessments', AssessmentController::class);
Route::resource('reviews', ReviewController::class);
Route::resource('courses', CourseController::class);
Route::resource('user', UserController::class);
});

require __DIR__.'/auth.php';
