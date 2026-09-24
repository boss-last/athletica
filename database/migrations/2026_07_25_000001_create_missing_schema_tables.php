<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Crée les tables référencées par les modèles mais qui manquaient de migration.
 * Tables concernées : activity_gps_points, activity_comments, activity_likes,
 * user_stats, user_badges, platform_connections.
 *
 * Cette migration corrige les erreurs 500 sur le dashboard et les pages
 * d'activité (notifications, commentaires, likes, carte GPS).
 */
return new class extends Migration
{
    public function up(): void
    {
        // --- Points GPS d'une activité (source de la carte Leaflet) ---
        if (!Schema::hasTable('activity_gps_points')) {
            Schema::create('activity_gps_points', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('activity_id');
                $table->dateTime('timestamp')->nullable();
                $table->decimal('latitude', 10, 7);
                $table->decimal('longitude', 10, 7);
                $table->decimal('altitude', 8, 2)->nullable();
                $table->decimal('heart_rate', 6, 2)->nullable();
                $table->decimal('speed', 8, 2)->nullable();
                $table->decimal('power', 8, 2)->nullable();
                $table->integer('cadence')->nullable();
                $table->decimal('temperature', 5, 2)->nullable();

                $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
                $table->index('activity_id');
            });
        }

        // --- Commentaires d'activité ---
        if (!Schema::hasTable('activity_comments')) {
            Schema::create('activity_comments', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('activity_id');
                $table->uuid('user_id');
                $table->text('content');
                $table->timestamps();

                $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['activity_id', 'created_at']);
            });
        }

        // --- Likes d'activité ---
        if (!Schema::hasTable('activity_likes')) {
            Schema::create('activity_likes', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('activity_id');
                $table->uuid('user_id');
                $table->timestamps();

                $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->unique(['activity_id', 'user_id']);
            });
        }

        // --- Statistiques agrégées par utilisateur ---
        if (!Schema::hasTable('user_stats')) {
            Schema::create('user_stats', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_id');
                $table->string('period'); // weekly, monthly, yearly
                $table->date('period_date');
                $table->integer('total_activities')->default(0);
                $table->decimal('total_distance_meters', 12, 2)->default(0);
                $table->integer('total_duration_seconds')->default(0);
                $table->integer('total_calories')->default(0);
                $table->decimal('total_elevation_gain', 10, 2)->default(0);
                $table->integer('avg_heart_rate')->nullable();
                $table->decimal('avg_speed', 8, 2)->nullable();
                $table->json('sports_breakdown')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'period', 'period_date']);
            });
        }

        // --- Liaison utilisateur <-> badge ---
        if (!Schema::hasTable('user_badges')) {
            Schema::create('user_badges', function (Blueprint $table) {
                $table->uuid('user_id');
                $table->uuid('badge_id');
                $table->timestamp('unlocked_at')->useCurrent();
                $table->timestamps();

                $table->primary(['user_id', 'badge_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('badge_id')->references('id')->on('badges')->onDelete('cascade');
            });
        }

        // --- Connexions aux plateformes tierces (Strava, Garmin...) ---
        if (!Schema::hasTable('platform_connections')) {
            Schema::create('platform_connections', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_id');
                $table->string('platform_name'); // strava, garmin...
                $table->string('platform_user_id')->nullable();
                $table->text('access_token')->nullable();
                $table->text('refresh_token')->nullable();
                $table->dateTime('token_expires_at')->nullable();
                $table->dateTime('last_synced_at')->nullable();
                $table->string('sync_status')->default('idle'); // idle, running, success, failed
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'platform_name']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_connections');
        Schema::dropIfExists('user_badges');
        Schema::dropIfExists('user_stats');
        Schema::dropIfExists('activity_likes');
        Schema::dropIfExists('activity_comments');
        Schema::dropIfExists('activity_gps_points');
    }
};
