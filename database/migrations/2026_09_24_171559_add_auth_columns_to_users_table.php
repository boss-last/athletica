<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'verification_token')) {
                $table->string('verification_token')->nullable();
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'reset_token')) {
                $table->string('reset_token')->nullable();
            }
            if (!Schema::hasColumn('users', 'reset_token_expires_at')) {
                $table->timestamp('reset_token_expires_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'login_code')) {
                $table->string('login_code', 6)->nullable();
            }
            if (!Schema::hasColumn('users', 'login_code_expires_at')) {
                $table->timestamp('login_code_expires_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_premium')) {
                $table->boolean('is_premium')->default(false);
            }
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'verification_token',
                'email_verified_at',
                'reset_token',
                'reset_token_expires_at',
                'login_code',
                'login_code_expires_at',
                'is_premium',
            ]);
        });
    }
};
