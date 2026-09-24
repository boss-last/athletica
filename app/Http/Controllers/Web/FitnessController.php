<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FitnessMetric;
use Illuminate\Http\Request;

class FitnessController extends Controller
{
    public function index()
    {
        $metrics = FitnessMetric::where('user_id', auth()->id())
            ->orderBy('metric_date', 'desc')
            ->take(30)
            ->get();

        $latest = $metrics->first();

        return view('fitness.index', compact('metrics', 'latest'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'metric_date' => 'required|date',
            'vo2max' => 'nullable|numeric',
            'resting_heart_rate' => 'nullable|integer',
            'heart_rate_variability' => 'nullable|numeric',
            'fitness_score' => 'nullable|integer',
            'fatigue_score' => 'nullable|integer',
            'form_score' => 'nullable|integer',
            'recovery_score' => 'nullable|integer',
        ]);

        FitnessMetric::create([
            'user_id' => auth()->id(),
            'metric_date' => $request->metric_date,
            'vo2max' => $request->vo2max,
            'resting_heart_rate' => $request->resting_heart_rate,
            'heart_rate_variability' => $request->heart_rate_variability,
            'fitness_score' => $request->fitness_score,
            'fatigue_score' => $request->fatigue_score,
            'form_score' => $request->form_score,
            'recovery_score' => $request->recovery_score,
        ]);

        return back()->with('success', 'Métrique ajoutée !');
    }
}