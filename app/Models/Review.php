<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public function assessment() {
        return $this->belongsTo('App\Models\Assessment');                // each review has an assessment it's associated with
    }

    public function reviewee() {                                        //each review was given by a reviewee user
        return $this->belongsTo('App\Models\User', 'reviewee_id');
    }

    public function reviewer() {                                        //each review was made by a reviewer
        return $this->belongsTo('App\Models\User', 'reviewer_id');
    }

}
