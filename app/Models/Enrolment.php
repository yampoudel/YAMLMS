<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrolment extends Model
{
    /** @use HasFactory<\Database\Factories\EnrolmentFactory> */
    use HasFactory;

    //Defining Custom Table
    protected $table = 'lms_enrolments';

    //Mass Assignment 
    protected $fillable = [
        'user_id', 
        'course_id',
        'enrolled_at',
        'enrolled_by'
    ];

    //Casting Fields
    protected $cast = [
        'enrolled_at' => 'datetime'
    ];

   //Get the course for this enrolment
    public function course(): BelongsTo
    {
       return $this->BelongsTo(Course::class); 
    }

    //Get the user for this enrolment
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }   

    //Check if the enrolment already exists
    public static function alreadyExists(int $user_id, int $course_id): bool
    {
      return self::where('user_id', $user_id)
                   ->where('course_id', $course_id)
                   ->exists();
    }
}
