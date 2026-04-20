<?php

namespace App\Models;

use Database\Factories\LessonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    /** @use HasFactory<LessonFactory> */
    use HasFactory;

    // Different table
    protected $table = 'lms_lessons';

    // Mass assignment fields
    public $fillable = [
        'course_id',
        'title',
        'description',
        'status',
        'type',
        'content',
        'created_by',
        'position',
    ];

    // Cast fields
    public $casts = [
        'content' => 'array',
        'status' => 'string',
        'type' => 'string',
    ];

    // Get course for this lesson
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Get lesson created by for this lesson
    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by',
        );
    }
}
