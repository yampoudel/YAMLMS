<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $join = fake()->dateTimeBetween('-5 years', '-1 year');

        return [
            'role' => fake()->randomElement(['Admin', 'Teacher', 'Learner']),
            'login' => Str::random(10),
            'first_name' => fake()->firstname(),
            'last_name' => fake()->lastname(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'birth_date' => fake()->dateTimeBetween('-60 years', '-16 years')->format('Y-m-d'),
            'mobile' => fake()->numerify('##########'),
            'phone' => fake()->numerify('##########'),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'suburb' => fake()->streetName(),
            'postcode' => fake()->numerify('####'),
            'join_date' => $join->format('Y-m-d'),
            'last_login' => fake()->dateTimeBetween($join, 'now')->format('Y-m-d'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function learner(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'Learner',
        ]);
    }
}
