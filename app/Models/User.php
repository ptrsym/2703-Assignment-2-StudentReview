<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Group;

class User extends Authenticatable
{
    public function courses(){
        return $this->belongsToMany('App\Models\Course', 'enrolments');               // each user can be enrolled in many courses
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');     // each user can be a part of many groups
    }

    public function receivedReviews(){
        return $this->hasMany('App\Models\Review', 'reviewee_id');                      // each user has many reviews made for them
    }

    public function givenReviews(){
        return $this->hasMany('App\Models\Review', 'reviewer_id');                       // each user can make many reviews
    }

    public function assessments() {
        return $this->belongsToMany('App\Models\Assessment', 'assessment_user')         // each user has a different score for many assessments
                    ->withPivot('score');
    }

    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'snumber',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
