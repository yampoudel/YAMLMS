<?php

use App\Models\Course;
use App\Models\User;

it('prevents a teacher from editing another teacher course', function () {
    // Create two teachers
    $teacherA = User::factory()->create(['role' => 'Teacher']);
    $teacherB = User::factory()->create(['role' => 'Teacher']);

    // Create a course own by teacher B
    $course = Course::factory()->create(['created_by' => $teacherB->id]);

    // Act as teacher A is visiting the edit page of teacher B
    $response = $this->actingAs($teacherA)
        ->get(route('courses.edit', $course));

    // Assert that teacher A is redirected (because of gate:denies)
    $response->assertRedirect(route('courses.index'));
    $response->assertSessionHas('error', 'You are not authorized to edit this course.');

});
