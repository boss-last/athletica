<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('badges')) {
            Schema::create('badges', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('icon_url')->nullable();
                $table->string('category')->nullable();
                $table->json('criteria')->nullable();
                $table->integer('points')->default(0);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
