<?php

use App\Models\User;

it('prevents teacher to enrol course to the admin', function() {
    //Get Teacher and Admin user
    $teacher = User::factory()->create(['role' => 'Teacher']);
    $admin = User::factory()->create(['role' => 'Admin']);

    //Acting as teacher trying to enrol to the course
    $response = $this->actingAs($teacher)
                    ->get(route('enrolments.create', $admin));
    
    //assert the teacher is redirect to user dashboard as no permit
    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('error', 'You are not authorized to enrol this user.');

});

