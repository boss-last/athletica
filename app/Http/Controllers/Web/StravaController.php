<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PlatformConnection;
use App\Services\StravaService;

class StravaController extends Controller
{
    private $stravaService;

    public function __construct(StravaService $stravaService)
    {
        $this->stravaService = $stravaService;
    }

    // Rediriger vers Strava
    public function redirect()
    {
        return redirect($this->stravaService->getAuthUrl());
    }

    // Callback après autorisation
    public function callback()
    {
        $code = request('code');
        $tokens = $this->stravaService->getToken($code);

        if (!isset($tokens['access_token'])) {
            return redirect('/dashboard')->with('error', 'Échec de connexion à Strava');
        }

        // Sauvegarder la connexion
        PlatformConnection::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'platform_name' => 'strava',
            ],
            [
                'platform_user_id' => $tokens['athlete']['id'],
                'access_token' => $tokens['access_token'],
                'refresh_token' => $tokens['refresh_token'],
                'token_expires_at' => now()->addSeconds($tokens['expires_in']),
                'metadata' => $tokens['athlete'],
                'sync_status' => 'active',
            ]
        );

        // Première synchronisation
        $newActivities = $this->stravaService->syncActivities(auth()->id());

        return redirect('/dashboard')
            ->with('success', 'Strava connecté ! ' . count($newActivities) . ' activités importées.');
    }

    // Synchroniser manuellement
    public function sync()
    {
        $newActivities = $this->stravaService->syncActivities(auth()->id());

        return back()->with('success', count($newActivities) . ' nouvelles activités synchronisées !');
    }
}
