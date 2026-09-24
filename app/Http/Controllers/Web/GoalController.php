<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Notifications\GoalAchievedNotification;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    // Liste des objectifs
    public function index()
    {
        $goals = Goal::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('goals.index', compact('goals'));
    }

    // Formulaire de création
    public function create()
    {
        return view('goals.create');
    }

    // Enregistrer un objectif
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'goal_type' => 'required|string',
            'target_value' => 'required|numeric',
            'sport_type' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        $goal = new Goal();
        $goal->user_id = auth()->id();
        $goal->title = $request->title;
        $goal->description = $request->description;
        $goal->goal_type = $request->goal_type;
        $goal->target_value = $request->target_value;
        $goal->current_value = 0;
        $goal->sport_type = $request->sport_type;
        $goal->start_date = $request->start_date;
        $goal->end_date = $request->end_date;
        $goal->is_completed = 0;  // ← Changé
        $goal->completed_at = null;  // ← Changé
        $goal->save();

        return redirect('/goals')->with('success', 'Objectif créé avec succès !');
    }

    // Mettre à jour la progression
    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'current_value' => 'required|numeric|min:0',
        ]);

       $goal = Goal::where('user_id', auth()->id())->findOrFail($id);
    $goal->current_value = $request->current_value;

    if ($goal->current_value >= $goal->target_value && !$goal->is_completed) {
        $goal->is_completed = 1;
        $goal->completed_at = now();
        $goal->save();

        // Notification
        auth()->user()->notify(new GoalAchievedNotification($goal));
    } else {
        $goal->save();
    }

    return back()->with('success', 'Progression mise à jour !');
    }

    // Supprimer un objectif
    public function destroy($id)
    {
        $goal = Goal::where('user_id', auth()->id())->findOrFail($id);
        $goal->delete();

        return back()->with('success', 'Objectif supprimé.');
    }
}
