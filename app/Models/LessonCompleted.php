<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonCompleted extends Model
{
    // Customized table intialize
    protected $table = 'lms_lessons_completed';

    // Mass assingnments
    protected $fillable = [
        'user_id',
        'lesson_id',
        'course_id',
        'started_at',
        'completed_at',
    ];
}
