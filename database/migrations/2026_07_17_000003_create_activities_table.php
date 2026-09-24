<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('activities')) {
            Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('platform_connection_id')->nullable();
            $table->string('external_id')->nullable();
            $table->string('sport_type');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->decimal('distance_meters', 10, 2)->nullable();
            $table->integer('calories_burned')->nullable();
            $table->integer('avg_heart_rate')->nullable();
            $table->integer('max_heart_rate')->nullable();
            $table->decimal('avg_speed', 8, 2)->nullable();
            $table->decimal('max_speed', 8, 2)->nullable();
            $table->decimal('elevation_gain', 8, 2)->nullable();
            $table->string('device_type')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_manual')->default(true);
            $table->boolean('is_private')->default(false);
            $table->integer('perceived_effort')->nullable();
            $table->json('raw_data')->nullable();
            $table->json('weather_data')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'start_time']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
