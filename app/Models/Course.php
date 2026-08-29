<?php

namespace App\Models;

use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    protected $table = 'lms_courses';

    // Mass assignment of fields
    protected $fillable = [
        'title',
        'description',
        'price',
        'status',
        'created_by',
        'image_path',
    ];

    // The attributes that should be appended to model arrays.
    protected $appends = [
        'course_image_url',
    ];

    // Get the enrolments for this course
    public function enrolments(): HasMany
    {
        return $this->HasMany(Enrolment::class, 'course_id');
    }

    // Get the list of users belongs to this course
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'lms_enrolments',
            'course_id',
            'user_id'
        )
            ->withPivot('status', 'enrolled_at', 'enrolled_by')
            ->withTimestamps();
    }

    // Get lessons for this course
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)
            ->orderBy('position', 'asc');
    }

    /**
     * Get the teacher/admin who created the course.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Return course progress for the course
     */
    public function courseProgress(): HasMany
    {
        // A course has many progress record (One for each student)
        return $this->hasMany(CourseCompleted::class, 'course_id');
    }

    /**
     * Get the full public URL for the course image.
     * Accessible in views as $course->course_image_url
     */
    public function getCourseImageUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return asset('storage/'.$this->image_path);
        }

        $nameParam = urlencode($this->title ?? 'Course');

        return "https://ui-avatars.com{$nameParam}&color=7F9CF5&background=EBF4FF";
    }
}
