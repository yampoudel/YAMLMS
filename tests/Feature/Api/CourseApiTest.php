<?php

namespace Test\Feature\Api;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check user(learner) can login and get token
     */
    public function test_learner_can_login_and_generate_token(): void
    {
        // Get user
        $learner = User::factory()->learner()
            ->create(['password' => bcrypt('password')]);

        // Call api and get response
        $response = $this->postJson('/api/login', [
            'email' => $learner->email,
            'password' => 'password',
            'device_name' => 'TestDevice',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user']);
    }

    /**
     * Check user(learner) can see only enrolled courses
     */
    public function test_authenticated_learner_can_view_enrolled_courses(): void
    {
        // Prepare data for test
        $learner = User::factory()->learner()->create();
        $course = Course::factory()->create();

        $learner->courses()->attach($course);

        // Use actingAs with 'sanctum' to simulate a token login
        $response = $this->actingAs($learner, 'sanctum')
            ->getJson('/api/my-progress');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => $course->title]);
    }

    /**
     * Check user(learner) cannot view unassigned course
     */
    public function test_learner_cannot_view_unassigned_course_details(): void
    {
        // Prepare data for test
        $learner = User::factory()->learner()->create();
        $unassigned_course = Course::factory()->create();

        // Use actingAs with 'sanctum'
        $response = $this->actingAs($learner, 'sanctum')
            ->getJson('/api/courses/'.$unassigned_course->id);

        $response->assertStatus(403);
    }
}
