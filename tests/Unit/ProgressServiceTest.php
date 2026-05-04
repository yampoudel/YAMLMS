<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProgressService $service;

    protected function setup(): void
    {
        parent::setup();

        $this->service = new ProgressService;
    }

    /**
     * Test that progress is 50% when 1 of 2 lessons is done.
     */
    public function test_it_calculate_fifty_percent_progress(): void
    {
        // Arrange data for testing
        $user = User::factory()->create();
        $course = Course::factory()->create();

        // Create two lessons for this course
        $lessons = Lesson::factory()->count(2)->create(['course_id' => $course->id]);

        // Act(Do the action)
        // We mark first lesson as complete
        $this->service->completeLesson($user, $course, $lessons->first());

        // Checking assertEquals(expected, actual);
        $this->assertEquals(50, $this->service->calculatePercentage($user, $course));
    }

    /**
     * Check return 0 if course has no lessons
     */
    public function test_it_returns_zero_if_course_has_no_lessons(): void
    {
        // Arrange data for testing
        $user = User::factory()->create();
        $course = Course::factory()->create();

        // Checking assertEquals(expected, actual);
        $this->assertEquals(0, $this->service->calculatePercentage($user, $course));
    }
}
