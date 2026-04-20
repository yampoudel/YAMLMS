<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrolment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a specific Master Admin for you to log in with
        $admin = User::factory()->create([
            'first_name' => 'Yam',
            'last_name' => 'User',
            'email' => 'rahulpaudel2015@outlook.com',
            'role' => 'Admin',
            'password' => bcrypt('Yam@54321'),
        ]);

        // 2. Create 3 Teachers, each owning 2 Courses, each with 5 Lessons
        User::factory(3)->create(['role' => 'Teacher'])->each(function ($teacher) {

            // For EACH teacher, create 2 Courses
            Course::factory(2)->create([
                'created_by' => $teacher->id,
            ])->each(function ($course) use ($teacher) {

                // For EACH course, create 5 Lessons (linked to both Course and Teacher)
                Lesson::factory(5)->create([
                    'course_id' => $course->id,
                    'created_by' => $teacher->id,
                ]);
            });
        });

        // 3. Create 10 Students
        $learners = User::factory(10)->create(['role' => 'Learner']);

        // 4. Randomly enrol students into courses using the Admin's ID
        $courses = Course::all();

        $learners->each(function ($learner) use ($courses, $admin) {
            Enrolment::factory()->create([
                'user_id' => $learner->id,
                'course_id' => $courses->random()->id,
                'enrolled_by' => $admin->id,
            ]);
        });
    }
}
