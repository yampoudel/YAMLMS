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
        Schema::create('lms_enrolments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('lms_users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('lms_courses')->cascadeOnDelete();
            $table->timestamp('enrolled_at')->useCurrent();
            $table->foreignId('enrolled_by')->nullable()->constrained('lms_users')->nullOnDelete();
            $table->timestamps();

            //No duplicate enrolments
            $table->unique(['user_id', 'course_id']);
    
            //For search by easier with enrolled date
            $table->index('enrolled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_enrolments');
    }
};
