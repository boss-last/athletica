<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('challenge_participants')) {
            Schema::create('challenge_participants', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('challenge_id');
            $table->decimal('current_progress', 10, 2)->default(0);
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->primary(['user_id', 'challenge_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('challenge_id')->references('id')->on('challenges')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('challenge_participants');
    }
};
