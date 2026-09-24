<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('training_sessions')) {
            Schema::create('training_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('plan_id');
            $table->integer('week_number');
            $table->integer('day_number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('sport_type')->nullable();
            $table->integer('planned_duration')->nullable();
            $table->decimal('planned_distance', 8, 2)->nullable();
            $table->string('intensity')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('plan_id')->references('id')->on('training_plans')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
