<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TrainingPlan;
use App\Models\TrainingSession;

class TrainingController extends Controller
{
    public function index()
    {
        $plans = TrainingPlan::with('coach', 'sessions')
            ->where('is_public', 1)
            ->orWhere('coach_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('training.index', compact('plans'));
    }

    public function show($id)
    {
        $plan = TrainingPlan::with('sessions')->findOrFail($id);

        return view('training.show', compact('plan'));
    }

    public function session($id)
    {
        $session = TrainingSession::with('plan')->findOrFail($id);

        return view('training.session', compact('session'));
    }
}
