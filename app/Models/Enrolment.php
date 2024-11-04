<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrolment extends Model
{

    //join table
    
        // Each enrolment belongs to a single user
        public function user()
        {
            return $this->belongsTo('App\Models\User');
        }
    
        // Each enrolment belongs to a single course
        public function course()
        {
            return $this->belongsTo('App\Models\Course');
        }

    use HasFactory;
}
