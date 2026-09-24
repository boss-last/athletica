<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Activities", description="Gestion des activités sportives")
 */
class ActivityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/activities",
     *     tags={"Activities"},
     *     summary="Liste des activités de l'utilisateur",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="page", in="query", required=false, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Liste des activités")
     * )
     */
    public function index(Request $request)
    {
        $activities = Activity::where('user_id', $request->user()->id)
            ->orderBy('start_time', 'desc')
            ->paginate(20);

        return response()->json($activities);
    }

    /**
     * @OA\Post(
     *     path="/activities",
     *     tags={"Activities"},
     *     summary="Créer une nouvelle activité",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"sport_type","start_time"},
     *             @OA\Property(property="sport_type", type="string", example="running"),
     *             @OA\Property(property="start_time", type="string", format="date-time"),
     *             @OA\Property(property="duration_seconds", type="integer", example=1800),
     *             @OA\Property(property="distance_meters", type="number", example=5000),
     *             @OA\Property(property="calories_burned", type="integer", example=350)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Activité créée")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'sport_type' => 'required|string',
            'start_time' => 'required|date',
            'duration_seconds' => 'nullable|integer',
            'distance_meters' => 'nullable|numeric',
        ]);

        $activity = Activity::create([
            'user_id' => $request->user()->id,
            'sport_type' => $request->sport_type,
            'start_time' => $request->start_time,
            'duration_seconds' => $request->duration_seconds,
            'distance_meters' => $request->distance_meters,
            'calories_burned' => $request->calories_burned,
            'avg_heart_rate' => $request->avg_heart_rate,
            'is_manual' => true,
            'notes' => $request->notes,
        ]);

        return response()->json($activity, 201);
    }

    /**
     * @OA\Get(
     *     path="/activities/{id}",
     *     tags={"Activities"},
     *     summary="Détail d'une activité",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Détail de l'activité"),
     *     @OA\Response(response=404, description="Activité non trouvée")
     * )
     */
    public function show($id)
    {
        $activity = Activity::with(['gpsPoints', 'comments.user', 'likes'])
            ->findOrFail($id);

        return response()->json($activity);
    }

    /**
     * @OA\Put(
     *     path="/activities/{id}",
     *     tags={"Activities"},
     *     summary="Modifier une activité",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="notes", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Activité modifiée")
     * )
     */
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $activity->update($request->only(['name', 'description', 'notes', 'is_private']));

        return response()->json($activity);
    }

    /**
     * @OA\Delete(
     *     path="/activities/{id}",
     *     tags={"Activities"},
     *     summary="Supprimer une activité",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Activité supprimée")
     * )
     */
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();

        return response()->json(['message' => 'Activité supprimée']);
    }

    /**
     * @OA\Get(
     *     path="/stats",
     *     tags={"Activities"},
     *     summary="Statistiques de la semaine",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Statistiques retournées")
     * )
     */
    public function stats(Request $request)
    {
        $user = $request->user();

        $stats = Activity::where('user_id', $user->id)
            ->where('start_time', '>=', now()->startOfWeek())
            ->selectRaw('
                COUNT(*) as total,
                SUM(distance_meters) as total_distance,
                SUM(duration_seconds) as total_duration,
                SUM(calories_burned) as total_calories,
                AVG(avg_heart_rate) as avg_hr
            ')
            ->first();

        return response()->json($stats);
    }
}
