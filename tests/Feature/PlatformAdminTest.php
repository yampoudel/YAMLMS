<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;

it('creates a platform admin from the factory', function () {
    $user = User::factory()->platformAdmin()->create();

    expect($user->isPlatformAdmin())->toBeTrue()
        ->and($user->isAdmin())->toBeTrue();
});

it('does not treat a normal admin as a platform admin', function () {
    $admin = User::factory()->admin()->create();

    expect($admin->isAdmin())->toBeTrue()
        ->and($admin->isPlatformAdmin())->toBeFalse();
});

it('does not treat a learner as a platform admin', function () {
    $learner = User::factory()->learner()->create();

    expect($learner->isPlatformAdmin())->toBeFalse();
});

it('cannot promote itself through the profile form', function () {
    $learner = User::factory()->learner()->create();

    // Learner tries to send extra fields along with a normal profile update
    $this->actingAs($learner)->patch(route('profile.update'), [
        'first_name' => 'Changed',
        'last_name' => 'Name',
        'email' => $learner->email,
        'role' => 'Admin',
        'is_platform_admin' => true,
    ]);

    $learner->refresh();

    // Normal fields update, privileged fields do not
    expect($learner->first_name)->toBe('Changed')
        ->and($learner->role)->toBe('Learner')
        ->and($learner->isPlatformAdmin())->toBeFalse();
});

it('creates a platform admin with the artisan command', function () {
    $this->artisan('platform:create-admin', ['email' => 'owner@example.test'])
        ->assertSuccessful();

    $user = User::where('email', 'owner@example.test')->first();

    expect($user)->not->toBeNull()
        ->and($user->isPlatformAdmin())->toBeTrue()
        ->and($user->role)->toBe('Admin')
        ->and($user->email_verified_at)->not->toBeNull();
});

it('promotes an existing user without changing the password', function () {
    $user = User::factory()->learner()->create();
    $oldHash = $user->password;

    $this->artisan('platform:create-admin', ['email' => $user->email])
        ->assertSuccessful();

    $user->refresh();

    expect($user->isPlatformAdmin())->toBeTrue()
        ->and($user->role)->toBe('Admin')
        ->and($user->password)->toBe($oldHash);
});

it('seeds a platform admin outside production', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::where('email', env('SEED_ADMIN_EMAIL', 'admin@example.test'))->first();

    expect($admin)->not->toBeNull()
        ->and($admin->isPlatformAdmin())->toBeTrue();
});

it('refuses to seed demo data in production', function () {
    $this->app['env'] = 'production';

    // Run the seeder directly, so db:seed's own confirmation prompt is not involved
    (new DatabaseSeeder)->run();

    expect(User::count())->toBe(0);
});
