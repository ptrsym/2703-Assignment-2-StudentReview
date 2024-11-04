<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentUser extends Model
{
    use HasFactory;

            // Define the relationship with User
        public function user()
        {
            return $this->belongsTo('App\Models\User');  // join table
        }
    
        // Define the relationship with assessment
        public function assessment()
        {
            return $this->belongsTo('App\Models\Assessment');
        }
}


