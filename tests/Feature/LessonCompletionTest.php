<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonCompletionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test enrol user can complete the lesson
     */
    public function test_an_enrolled_user_can_complete_a_lesson_via_the_route(): void
    {
        // Arrange Data for test
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'position' => 1,
        ]);

        // Act: Login and post to the completion route
        $response = $this->actingAs($user)
            ->post(route('lessons.complete', [$course, $lesson]));

        // Asset : check it worked as data is in db
        $this->assertDatabaseHas('lms_lessons_completed', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);

        // Check if the use is redirected to the dashboard
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');
    }
}
