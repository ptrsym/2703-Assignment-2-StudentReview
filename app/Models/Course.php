<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public function assessments() {
        return $this->hasMany('App\Models\Assessment');      // each assessment has an associated course
    }

    public function users() {                                 // each course can have many uses
        return $this->belongsToMany('App\Models\User', 'enrolments');
    }

}
