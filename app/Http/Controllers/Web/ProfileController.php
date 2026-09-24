<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Goal;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('athleteProfile', 'badges', 'followers', 'following');

        $totalStats = Activity::where('user_id', $user->id)
            ->selectRaw('
                COUNT(*) as total_activities,
                COALESCE(SUM(distance_meters), 0) as total_distance,
                COALESCE(SUM(duration_seconds), 0) as total_duration,
                COALESCE(SUM(calories_burned), 0) as total_calories,
                COALESCE(SUM(elevation_gain), 0) as total_elevation
            ')
            ->first();

        $records = [
            'longest_run' => Activity::where('user_id', $user->id)->where('sport_type', 'running')->max('distance_meters'),
            'fastest_pace' => Activity::where('user_id', $user->id)->where('sport_type', 'running')->whereNotNull('avg_speed')->max('avg_speed'),
            'most_calories' => Activity::where('user_id', $user->id)->max('calories_burned'),
            'longest_duration' => Activity::where('user_id', $user->id)->max('duration_seconds'),
        ];

        $completedGoals = Goal::where('user_id', $user->id)->where('is_completed', 1)->count();
        $badges = $user->badges;

        $recentActivities = Activity::where('user_id', $user->id)
            ->orderBy('start_time', 'desc')
            ->take(5)
            ->get();

        return view('profile.index', compact('user', 'totalStats', 'records', 'completedGoals', 'badges', 'recentActivities'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $user = auth()->user();
        $user->update($request->only(['full_name', 'bio', 'gender', 'date_of_birth']));

        if ($request->hasAny(['height', 'weight', 'primary_sport', 'fitness_level'])) {
            $user->athleteProfile()->updateOrCreate(
                ['user_id' => $user->id],
                $request->only(['height', 'weight', 'primary_sport', 'fitness_level'])
            );
        }

        return back()->with('success', 'Profil mis à jour !');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        // Supprimer l'ancien avatar
        if ($user->avatar_url && str_contains($user->avatar_url, '/avatars/')) {
            $oldFile = public_path(ltrim($user->avatar_url, '/'));
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        // Créer le dossier s'il n'existe pas
        $avatarsPath = public_path('avatars');
        if (!file_exists($avatarsPath)) {
            mkdir($avatarsPath, 0777, true);
        }

        // Sauvegarder le nouveau fichier
        $file = $request->file('avatar');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($avatarsPath, $filename);

        $user->avatar_url = '/avatars/' . $filename;
        $user->save();

        return back()->with('success', 'Avatar mis à jour !');
    }
}
