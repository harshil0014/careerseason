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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();

            // For now we hardcode this track, but keep the column for future
            $table->string('track')->default('sde_internship');

            // Fixed 6-week season in v1
            $table->unsignedTinyInteger('weeks')->default(6);

            // Current week pointer (1..6)
            $table->unsignedTinyInteger('current_week')->default(1);

            // How many hours per week the student *promised* to give
            $table->unsignedSmallInteger('target_hours_per_week');

            // JSON array of priority tracks: ["dsa", "projects", "networking", ...]
            $table->json('priority_tracks');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
