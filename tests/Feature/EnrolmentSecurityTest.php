<?php

use App\Models\User;

it('prevents teacher to enrol course to the admin', function () {
    // Get Teacher and Admin user
    $teacher = User::factory()->create(['role' => 'Teacher']);
    $admin = User::factory()->create(['role' => 'Admin']);

    // Acting as teacher trying to enrol to the course
    $response = $this->actingAs($teacher)
        ->get(route('enrolments.create', $admin));

    // assert the teacher is redirect to user dashboard as no permit
    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('error', 'You are not authorized to enrol this user.');

});

it('prevents teacher to enrol course to another teacher', function () {
    // Get two teachers
    $teacherA = User::factory()->create(['role' => 'Teacher']);
    $teacherB = User::factory()->create(['role' => 'Teacher']);

    // Acting as teacherA trying to enrol for teacher B
    $response = $this->actingAs($teacherA)
        ->get(route('enrolments.create', $teacherB));

    // Assert teacherA is redirect back to users dashboard as no permit
    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('error', 'You are not authorized to enrol this user.');

});
