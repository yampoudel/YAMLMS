<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrolment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database (demo data, local/testing only).
     */
    public function run(): void
    {
        // Never seed demo accounts into production
        if (app()->isProduction()) {
            $this->command?->error('Refusing to seed demo data in production.');

            return;
        }

        // Create a demo Admin. Credentials come from .env, or are generated.
        $adminEmail = env('SEED_ADMIN_EMAIL', 'admin@example.test');
        $adminPassword = env('SEED_ADMIN_PASSWORD') ?: Str::password(16);

        $admin = User::factory()->platformAdmin()->create([
            'first_name' => 'Demo',
            'last_name' => 'Admin',
            'email' => $adminEmail,
            'role' => 'Admin',
            'password' => $adminPassword, // hashed automatically by the model cast
        ]);

        // Show the generated password once in the terminal (never stored in code)
        if (! env('SEED_ADMIN_PASSWORD')) {
            $this->command?->warn("Demo admin: {$adminEmail} / {$adminPassword}");
        }

        // Create 3 Teachers, each owning 2 Courses, each with 5 Lessons
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

        // Create 10 Students
        $learners = User::factory(10)->create(['role' => 'Learner']);

        // Randomly enrol students into courses using the Admin's ID
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
