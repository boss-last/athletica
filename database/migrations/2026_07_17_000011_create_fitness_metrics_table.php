<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('fitness_metrics')) {
            Schema::create('fitness_metrics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->date('metric_date');
            $table->decimal('vo2max', 5, 2)->nullable();
            $table->integer('fitness_score')->nullable();
            $table->integer('fatigue_score')->nullable();
            $table->integer('form_score')->nullable();
            $table->integer('recovery_score')->nullable();
            $table->integer('resting_heart_rate')->nullable();
            $table->integer('heart_rate_variability')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fitness_metrics');
    }
};
