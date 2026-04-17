<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Course;
use App\Models\Enrolment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a specific Master Admin for you to log in with
        User::factory()->create([
            'first_name' => 'Yam',
            'last_name' => 'User',
            'email' => 'rahulpaudel2015@outlook.com',
            'role' => 'Admin',
            'password' => bcrypt('Yam@54321'),
        ]);

        // Create 3 Teachers, each owning 2 Courses
        User::factory(3)->create(['role' => 'Teacher'])->each(function ($teacher) {
            Course::factory(2)->create(['created_by' => $teacher->id]);
        });

        // Create 10 Students
        $learners = User::factory(10)->create(['role' => 'Learner']);

        // Randomly enrol some students into random courses
        $courses = Course::all();
        foreach ($learners as $learner) {
            Enrolment::factory()->create([
                'user_id' => $learner->id,
                'course_id' => $courses->random()->id,
                'enrolled_by' => User::where('role', 'Admin')->first()->id,
            ]);
        }
    }
}
