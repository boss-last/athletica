<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Challenges", description="Gestion des challenges")
 */
class ChallengeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/challenges",
     *     tags={"Challenges"},
     *     summary="Liste des challenges publics",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Liste retournée")
     * )
     */
    public function index()
    {
        $challenges = Challenge::where('is_public', true)
            ->with('creator')
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json($challenges);
    }

    /**
     * @OA\Post(
     *     path="/challenges",
     *     tags={"Challenges"},
     *     summary="Créer un challenge",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","challenge_type","target_value","start_date","end_date"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="challenge_type", type="string"),
     *             @OA\Property(property="target_value", type="number"),
     *             @OA\Property(property="start_date", type="string", format="date"),
     *             @OA\Property(property="end_date", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Challenge créé")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'challenge_type' => 'required|string',
            'target_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $challenge = Challenge::create([
            'creator_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'challenge_type' => $request->challenge_type,
            'target_value' => $request->target_value,
            'sport_type' => $request->sport_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_public' => $request->is_public ?? true,
        ]);

        return response()->json($challenge, 201);
    }

    /**
     * @OA\Post(
     *     path="/challenges/{id}/join",
     *     tags={"Challenges"},
     *     summary="Rejoindre un challenge",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Participation enregistrée")
     * )
     */
    public function join(Request $request, $id)
    {
        $challenge = Challenge::findOrFail($id);

        $challenge->participants()->attach($request->user()->id, [
            'current_progress' => 0,
            'joined_at' => now(),
        ]);

        return response()->json(['message' => 'Participation enregistrée']);
    }
}
