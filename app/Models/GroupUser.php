<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    use HasFactory;

        // Define the relationship with User
        public function user()
        {
            return $this->belongsTo('App\Models\User');    // the user the group belongs to
        }
    
        // Define the relationship with Group
        public function group()                            // the group the user belongs to
        {
            return $this->belongsTo('App\Models\Group');
        }
}
