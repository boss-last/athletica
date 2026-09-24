<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tables de paiement Stripe (stripe-php seul, sans Cashier).
 * - subscriptions : un abonnement actif/annulé par utilisateur
 * - payments : historique des paiements reçus via webhook
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_id');
                $table->string('plan_type'); // free, premium, pro, club
                $table->string('stripe_customer_id')->nullable();
                $table->string('stripe_subscription_id')->nullable()->unique();
                $table->string('stripe_price_id')->nullable();
                $table->string('status')->default('incomplete'); // incomplete, active, past_due, canceled
                $table->decimal('amount', 8, 2)->default(0);
                $table->string('currency', 3)->default('EUR');
                $table->dateTime('current_period_end')->nullable();
                $table->timestamp('canceled_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id', 'status']);
            });
        }

        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('user_id');
                $table->uuid('subscription_id')->nullable();
                $table->string('stripe_payment_intent_id')->nullable()->unique();
                $table->string('stripe_invoice_id')->nullable();
                $table->decimal('amount', 8, 2);
                $table->string('currency', 3)->default('EUR');
                $table->string('status'); // paid, failed, refunded...
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');
                $table->index(['user_id', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('subscriptions');
    }
};
