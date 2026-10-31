<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreatePlatformAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'platform:create-admin
                            {email : Email address of the platform admin}
                            {--first-name=Platform : First name (new users only)}
                            {--last-name=Admin : Last name (new users only)}
                            {--reset-password : Generate a new password for an existing user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or promote a platform (system owner) admin';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = strtolower($this->argument('email'));

        $validator = Validator::make(
            ['email' => $email],
            ['email' => ['required', 'email', 'max:255']]
        );

        if ($validator->fails()) {
            $this->error('Please provide a valid email address.');

            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();
        $password = null;

        if (! $user) {
            // New user: generate a strong password
            $password = Str::password(20);

            $user = User::create([
                'first_name' => $this->option('first-name'),
                'last_name' => $this->option('last-name'),
                'email' => $email,
                'password' => $password,
                'role' => 'Admin',
                'status' => 'Active',
                'login' => Str::random(10),
            ]);
        } elseif ($this->option('reset-password')) {
            // Existing user: only change the password when asked
            $password = Str::password(20);
            $user->password = $password;
        }

        // is_platform_admin and email_verified_at are not mass assignable
        $user->forceFill([
            'role' => 'Admin',
            'status' => 'Active',
            'is_platform_admin' => true,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        $this->info("{$user->email} is now a platform admin.");

        if ($password) {
            $this->warn("Password (shown once): {$password}");
        }

        return self::SUCCESS;
    }
}
