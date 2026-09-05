<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'lms_users';

    /**
     * The attributes that are mass assignable
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
        'login',
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
        'birth_date',
        'phone',
        'mobile',
        'country',
        'city',
        'postcode',
        'suburb',
        'image_path',
    ];

    /**
     * The attributes that should be appended to model arrays.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'name',
        'image_path_url',
    ];

    // Get name
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',

        // adding custom datefield here
        'join_date' => 'datetime',
        'last_login' => 'datetime',
        'birth_date' => 'date',
    ];

    // Get the list of enrolments for this user
    public function enrolments(): HasMany
    {
        return $this->HasMany(Enrolment::class);
    }

    // Get the list of courses belongs to this user
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(
            Course::class,
            'lms_enrolments', // bridging table
            'user_id',
            'course_id'
        )
            ->withPivot('status', 'enrolled_at', 'enrolled_by')
            ->withTimestamps();
    }

    // Check super admin
    public function isSuperAdmin(): bool
    {
        return $this->email === config('app.super_admin_email', env('SUPER_ADMIN_EMAIL'));
    }

    // Check admin user
    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    // Check teacher user
    public function isTeacher(): bool
    {
        return $this->role === 'Teacher';
    }

    // Check learner user
    public function isLearner(): bool
    {
        return $this->role === 'Learner';
    }

    /**
     * Return course progress for the user
     */
    public function courseProgress(): HasMany
    {
        return $this->hasMany(CourseCompleted::class, 'user_id');
    }

    /**
     * Return list of completed lessons for the user
     */
    public function completedLessons(): HasMany
    {
        return $this->hasMany(LessonCompleted::class, 'user_id');
    }

    /**
     * Get the full public URL for the user's profile image.
     * Accessible in views as $user->image_path_url
     */
    public function getImagePathUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return asset('storage/'.$this->image_path);
        }

        // Fixed path: added /api/?name=
        return 'https://ui-avatars.com/?name='.$this->first_name.' '.$this->last_name.'&color=7F9CF5&background=EBF4FF';
    }
}
