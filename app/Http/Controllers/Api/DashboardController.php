<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Goal;
use App\Models\UserStat;
use Illuminate\Http\Request;

/**
 * @OA\Tag(name="Dashboard", description="Tableau de bord")
 */
class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/dashboard",
     *     tags={"Dashboard"},
     *     summary="Données du tableau de bord",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Dashboard retourné")
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $weeklyStats = UserStat::where('user_id', $user->id)
            ->where('period', 'weekly')
            ->latest('period_date')
            ->first();

        $recentActivities = Activity::where('user_id', $user->id)
            ->orderBy('start_time', 'desc')
            ->take(5)
            ->get();

        $activeGoals = Goal::where('user_id', $user->id)
            ->where('is_completed', false)
            ->get();

        $connections = $user->platformConnections()->get();

        return response()->json([
            'weekly_stats' => $weeklyStats,
            'recent_activities' => $recentActivities,
            'active_goals' => $activeGoals,
            'connections' => $connections,
        ]);
    }
}
