<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute la colonne is_premium et premium_expires_at à la table users.
 * Piloté par les webhooks Stripe (voir StripeService::handleWebhook).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_premium')) {
                $table->boolean('is_premium')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('users', 'premium_expires_at')) {
                $table->dateTime('premium_expires_at')->nullable()->after('is_premium');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_premium', 'premium_expires_at']);
        });
    }
};
