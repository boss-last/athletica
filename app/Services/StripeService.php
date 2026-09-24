<?php

namespace App\Services;

use App\Models\User;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(User $user, string $plan): Session
    {
        $priceIds = [
            'premium' => config('services.stripe.premium_price_id'),
            'pro' => config('services.stripe.pro_price_id'),
            'club' => config('services.stripe.club_price_id'),
        ];

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceIds[$plan] ?? $priceIds['premium'],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => url('/premium/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/premium'),
            'customer_email' => $user->email,
            'metadata' => [
                'user_id' => $user->id,
                'plan' => $plan,
            ],
        ]);
    }

    public function handleWebhook(string $payload, string $signature): void
    {
        $event = Webhook::constructEvent(
            $payload,
            $signature,
            config('services.stripe.webhook_secret')
        );

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $userId = $session->metadata->user_id;
            $plan = $session->metadata->plan;

            User::where('id', $userId)->update([
                'is_premium' => true,
                'plan' => $plan,
            ]);
        }

        if ($event->type === 'customer.subscription.deleted') {
            $subscription = $event->data->object;
            $user = User::where('stripe_id', $subscription->customer)->first();
            if ($user) {
                $user->update(['is_premium' => false, 'plan' => null]);
            }
        }
    }

    public function getPlans(): array
    {
        return [
            'free' => ['name' => 'Gratuit', 'price' => 0, 'features' => ['5 activités', 'Stats de base']],
            'premium' => ['name' => 'Premium', 'price' => 4.99, 'features' => ['Activités illimitées', 'Strava Sync', 'Cartes GPS', 'Badges']],
            'pro' => ['name' => 'Pro', 'price' => 9.99, 'features' => ['Tout Premium', 'Coaching', 'Analytics avancés']],
            'club' => ['name' => 'Club', 'price' => 29.99, 'features' => ['Tout Pro', 'Équipe', 'Multi-coach']],
        ];
    }
}
