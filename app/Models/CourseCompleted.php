<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseCompleted extends Model
{
    // Define customized table
    protected $table = 'lms_courses_completed';

    // Mass assignments
    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'progress_percentage',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
