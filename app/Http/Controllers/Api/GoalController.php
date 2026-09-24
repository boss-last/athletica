<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Goals", description="Gestion des objectifs")
 */
class GoalController extends Controller
{
    /**
     * @OA\Get(
     *     path="/goals",
     *     tags={"Goals"},
     *     summary="Liste des objectifs",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Liste retournée")
     * )
     */
    public function index(Request $request)
    {
        $goals = Goal::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($goals);
    }

    /**
     * @OA\Post(
     *     path="/goals",
     *     tags={"Goals"},
     *     summary="Créer un objectif",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","goal_type","target_value","start_date","end_date"},
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="goal_type", type="string", enum={"distance","duration","frequency","calories"}),
     *             @OA\Property(property="target_value", type="number"),
     *             @OA\Property(property="start_date", type="string", format="date"),
     *             @OA\Property(property="end_date", type="string", format="date")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Objectif créé")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'goal_type' => 'required|string',
            'target_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $goal = Goal::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'goal_type' => $request->goal_type,
            'target_value' => $request->target_value,
            'sport_type' => $request->sport_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json($goal, 201);
    }

    /**
     * @OA\Delete(
     *     path="/goals/{id}",
     *     tags={"Goals"},
     *     summary="Supprimer un objectif",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Objectif supprimé")
     * )
     */
    public function destroy($id)
    {
        $goal = Goal::findOrFail($id);
        $goal->delete();

        return response()->json(['message' => 'Objectif supprimé']);
    }
}
