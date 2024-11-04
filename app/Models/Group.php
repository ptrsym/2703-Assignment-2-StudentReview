<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Assessment;

class Group extends Model
{
    use HasFactory;

    public function users() {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id');   // each group can have many users
    }

    public function assessment() {
        return $this->belongsTo('App\Models\Assessment');                                  // each group has an associated assessment item
    }

    protected $fillable = ['assessment_id'];


}
