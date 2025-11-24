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
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();

            // Relationship to Season
            $table->foreignId('season_id')->constrained()->cascadeOnDelete();

            // Week number = 1..6
            $table->unsignedTinyInteger('week_number');

            // Work Summary
            $table->unsignedSmallInteger('hours_dsa')->default(0);
            $table->unsignedSmallInteger('hours_projects')->default(0);
            $table->unsignedSmallInteger('hours_career')->default(0);

            $table->unsignedSmallInteger('problems_solved')->default(0);
            $table->unsignedSmallInteger('commits')->default(0);
            $table->unsignedSmallInteger('outreach_count')->default(0);

            // Proof (optional)
            $table->text('github_links')->nullable();
            $table->text('other_links')->nullable();

            // Reflection
            $table->text('biggest_win')->nullable();
            $table->text('biggest_excuse')->nullable();
            $table->string('next_week_fix')->nullable();

            // Score + verdict + recommendations
            $table->unsignedTinyInteger('score')->nullable();
            $table->string('verdict')->nullable();
            $table->json('recommendations')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_ins');
    }
};
