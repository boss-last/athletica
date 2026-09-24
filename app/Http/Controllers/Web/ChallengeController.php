<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\ChallengeParticipant;
use App\Notifications\ChallengeJoinedNotification;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    // Liste des challenges
    public function index()
    {
        $challenges = Challenge::with('creator')
            ->withCount('participants')
            ->orderBy('start_date', 'desc')
            ->get();

        $myChallenges = ChallengeParticipant::where('user_id', auth()->id())
            ->with('challenge.creator')
            ->get();

        return view('challenges.index', compact('challenges', 'myChallenges'));
    }

    // Créer un challenge
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'challenge_type' => 'required|string',
            'target_value' => 'required|numeric',
            'sport_type' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        $challenge = new Challenge();
        $challenge->creator_id = auth()->id();
        $challenge->title = $request->title;
        $challenge->description = $request->description;
        $challenge->challenge_type = $request->challenge_type;
        $challenge->target_value = $request->target_value;
        $challenge->sport_type = $request->sport_type;
        $challenge->start_date = $request->start_date;
        $challenge->end_date = $request->end_date;
        $challenge->is_public = 1;
        $challenge->save();

        // Le créateur participe automatiquement
        $challenge->participants()->attach(auth()->id(), [
            'current_progress' => 0,
            'joined_at' => now(),
        ]);

        return redirect('/challenges')->with('success', 'Challenge créé avec succès !');
    }

    // Participer à un challenge
    public function join($id)
    {
        $challenge = Challenge::findOrFail($id);

        // Vérifier si déjà participant
        if (!$challenge->participants()->where('user_id', auth()->id())->exists()) {
            $challenge->participants()->attach(auth()->id(), [
                'current_progress' => 0,
                'joined_at' => now(),
            ]);
            $challenge->creator->notify(new ChallengeJoinedNotification($challenge, auth()->user()));
        }

        return back()->with('success', 'Vous participez maintenant au challenge !');
    }

    // Voir le classement d'un challenge
    public function show($id)
    {
        $challenge = Challenge::with(['participants' => function ($query) {
            $query->orderBy('current_progress', 'desc');
        }])->findOrFail($id);

        return view('challenges.show', compact('challenge'));
    }
}
