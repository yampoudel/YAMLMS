<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        return $this->BelongsToMany(
            Course::class,
            'lms_enrolments',// bridging table
            'id',
            'user_id',
            'course_id'
        )->withPivot('enrolled_at', 'enrolled_by')
            ->withTimeStamps();
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
}
