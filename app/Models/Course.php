<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $table = 'lms_courses';
  
    //Mass assignment of fields
    protected  $fillable = ['title', 'description', 'created_by'];
}
