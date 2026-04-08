<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $table = 'lms_courses';
  
    //Mass assignment of fields
    protected  $fillable = [
        'title', 
        'description', 
        'created_by'
    ];

    //Get the enrolments for this course
    public function enrolments(): HasMany
    {
        return $this->HasMany(Enrolment::class);
    }

    //Get the list of users belongs to this course
    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'lms_enrolments', 'course_id','user_id' );
    }
}
