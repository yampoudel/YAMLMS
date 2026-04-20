<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::inRandomOrder()->first()?->id ?? Course::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'status' => fake()->RandomElement(['Active', 'Disabled']),
            'type' => fake()->RandomElement(['Default', 'Survey', 'Quiz']),
            'content' => [
                [
                    'type' => 'text',
                    'value' => '<p>'.fake()->paragraph().'</p>',
                ],
                [
                    'type' => 'video',
                    'value' => 'https://youtube.com',
                ],
                [
                    'type' => 'image',
                    'value' => fake()->image(),
                ],
            ],
            'created_by' => User::inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }
}
