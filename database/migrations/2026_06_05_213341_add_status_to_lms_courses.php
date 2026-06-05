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
        Schema::table('lms_courses', function (Blueprint $table) {
            $table->enum('status', ['Active', 'Disabled'])->default('Active')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lms_courses', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
