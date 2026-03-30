<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'lms_users';

   /**
    * The attributes that are mass assignable
    *@var array<int, string>
    */
    protected $fillable = [
        'role',
        'login',
        'firstname',
        'lastname',
        'email',
        'password',
        'status',
        'birthdate',
        'phone',
        'mobile',
        'country',
        'city',
        'postcode',
        'suburb'
    ];

    // Get name
    public function getNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',

        //adding custom datefield here
        'joindate' => 'datetime',
        'lastlogin' => 'datetime',
        'birthdate' => 'datetime'
    ];
}
