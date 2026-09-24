<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\StripeService;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    protected StripeService $stripe;

    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;
    }

    public function index()
    {
        $plans = $this->stripe->getPlans();
        $currentPlan = auth()->user()->plan ?? 'free';
        return view('premium.index', compact('plans', 'currentPlan'));
    }

    public function subscribe(string $plan)
    {
        $session = $this->stripe->createCheckoutSession(auth()->user(), $plan);
        return redirect($session->url);
    }

    public function success(Request $request)
    {
        return view('premium.success');
    }

    public function cancel()
    {
        return redirect('/premium')->with('info', 'Votre abonnement a été annulé.');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $this->stripe->handleWebhook($payload, $signature);
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
