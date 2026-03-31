<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lms_users', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['Admin', 'Teacher', 'Learner'])->default('Learner');
            $table->string('login')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // Good for security
            $table->string('password'); // REQUIRED for authentication
            $table->rememberToken();    // REQUIRED for "Remember Me" functionality
            $table->enum('status', ['Active', 'Disabled'])->default('Active');
            $table->date('birth_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('suburb')->nullable();
            $table->timestamp('join_date')->useCurrent();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_users');
    }
};
