<?php

namespace App\Models;

use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    protected $table = 'lms_courses';

    // Mass assignment of fields
    protected $fillable = [
        'title',
        'description',
        'created_by',
    ];

    // Get the enrolments for this course
    public function enrolments(): HasMany
    {
        return $this->HasMany(Enrolment::class);
    }

    // Get the list of users belongs to this course
    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(
            User::class,
            'lms_enrolments',
            'id',
            'course_id',
            'user_id'
        );
    }

    // Get lessons for this course
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)
            ->order_by('position', 'asc');
    }
}
