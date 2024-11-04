<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable =['title', 'instructions', 'review_req', 'score', 'due_date', 'course_id'];  //fillable for mass assignment


    public function groups() {                     // can have many groups associated with it
        return $this->hasMany('App\Models\Group');
    }

    public function reviews() {                        // has many reviews associated with it
        return $this->hasMany('App\Models\Review');
    }

    public function course() {                              // belongs to one course
        return $this->belongsTo('App\Models\Course');
    }

    // can pivot table where many users can be attached to one assignment all with their own score
    // also many assignments can be attached to one user
    
    public function users() {                                  
        return $this->belongsToMany('App\Models\User', 'assessment_user')
                    ->withPivot('score');
    }


}
