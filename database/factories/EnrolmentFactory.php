<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Enrolment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Enrolment>
 */
class EnrolmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Plan A: Use existing IDs | Plan B: Create new ones if DB is empty
            'user_id' => User::where('role', 'Learner')->inRandomOrder()->first()?->id ?? User::factory()->create(['role' => 'Learner'])->id,
            'course_id' => Course::inRandomOrder()->first()?->id ?? Course::factory(),
            'enrolled_by' => User::where('role', 'Admin')->inRandomOrder()->first()?->id ?? User::where('role', 'Teacher')->inRandomOrder()->first()?->id ?? 1,
            'enrolled_at' => now(),
        ];
    }
}
