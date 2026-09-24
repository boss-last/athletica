<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('training_plans')) {
            Schema::create('training_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('coach_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sport_type')->nullable();
            $table->string('difficulty_level')->nullable();
            $table->integer('duration_weeks')->default(4);
            $table->boolean('is_public')->default(true);
            $table->decimal('price', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('coach_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('training_plans');
    }
};
