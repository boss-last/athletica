<?php

namespace App\Services;

use App\Models\PlatformConnection;
use App\Models\Activity;
use App\Models\ActivityGpsPoint;
use Illuminate\Support\Facades\Http;

class StravaService
{
    private $clientId;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.strava.client_id');
        $this->clientSecret = config('services.strava.client_secret');
        $this->redirectUri = config('services.strava.redirect');
    }

    // URL d'autorisation Strava
    public function getAuthUrl()
    {
        $params = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'read,activity:read_all',
            'approval_prompt' => 'auto',
        ]);

        return 'https://www.strava.com/oauth/authorize?' . $params;
    }

    // Échanger le code contre un token
    public function getToken($code)
    {
        $response = Http::withOptions([
            'verify' => false,
        ])->post('https://www.strava.com/oauth/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]);

        return $response->json();
    }

    // Rafraîchir le token
    public function refreshToken($refreshToken)
    {
        $response = Http::withOptions([
            'verify' => false,
        ])->post('https://www.strava.com/oauth/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        return $response->json();
    }

    // Récupérer les activités Strava
    public function getActivities($accessToken, $page = 1, $perPage = 50)
    {
        $response = Http::withOptions([
            'verify' => false,
        ])->withToken($accessToken)
            ->get('https://www.strava.com/api/v3/athlete/activities', [
                'page' => $page,
                'per_page' => $perPage,
            ]);

        return $response->json();
    }

    // Récupérer les points GPS d'une activité
    public function getActivityStreams($accessToken, $activityId)
    {
        $response = Http::withOptions([
            'verify' => false,
        ])->withToken($accessToken)
            ->get("https://www.strava.com/api/v3/activities/{$activityId}/streams", [
                'keys' => 'time,latlng,distance,altitude,heartrate,velocity_smooth,cadence,watts',
                'key_by_type' => true,
            ]);

        return $response->json();
    }

    // Synchroniser toutes les activités
    public function syncActivities($userId)
    {
        $connection = PlatformConnection::where('user_id', $userId)
            ->where('platform_name', 'strava')
            ->first();

        if (!$connection) return [];

        if ($connection->token_expires_at && now()->gt($connection->token_expires_at)) {
            $tokens = $this->refreshToken($connection->refresh_token);
            if (isset($tokens['access_token'])) {
                $connection->update([
                    'access_token' => $tokens['access_token'],
                    'refresh_token' => $tokens['refresh_token'],
                    'token_expires_at' => now()->addSeconds($tokens['expires_in']),
                ]);
            }
        }

        $newActivities = [];
        $page = 1;

        do {
            $stravaActivities = $this->getActivities($connection->access_token, $page);
            if (empty($stravaActivities)) break;

            foreach ($stravaActivities as $stravaActivity) {
                $exists = Activity::where('external_id', $stravaActivity['id'])
                    ->where('platform_connection_id', $connection->id)
                    ->exists();

                if (!$exists) {
                    $activity = $this->importActivity($userId, $connection->id, $stravaActivity, $connection->access_token);
                    $newActivities[] = $activity;
                }
            }
            $page++;
        } while (count($stravaActivities) === 50);

        $connection->update(['last_synced_at' => now()]);
        return $newActivities;
    }

    private function importActivity($userId, $connectionId, $stravaActivity, $accessToken)
    {
        $activity = Activity::create([
            'user_id' => $userId,
            'platform_connection_id' => $connectionId,
            'external_id' => $stravaActivity['id'],
            'name' => $stravaActivity['name'],
            'sport_type' => $this->mapSportType($stravaActivity['type']),
            'start_time' => $stravaActivity['start_date'],
            'duration_seconds' => $stravaActivity['elapsed_time'],
            'distance_meters' => $stravaActivity['distance'],
            'calories_burned' => $stravaActivity['calories'] ?? 0,
            'avg_heart_rate' => $stravaActivity['average_heartrate'] ?? null,
            'max_heart_rate' => $stravaActivity['max_heartrate'] ?? null,
            'avg_speed' => $stravaActivity['average_speed'] ?? null,
            'max_speed' => $stravaActivity['max_speed'] ?? null,
            'elevation_gain' => $stravaActivity['total_elevation_gain'] ?? 0,
            'device_type' => 'strava',
            'is_manual' => 0,
            'raw_data' => json_encode($stravaActivity),
        ]);

        return $activity;
    }

    private function mapSportType($stravaType)
    {
        $map = [
            'Run' => 'running',
            'TrailRun' => 'trail',
            'VirtualRun' => 'running',
            'Ride' => 'cycling',
            'VirtualRide' => 'cycling',
            'Swim' => 'swimming',
            'Walk' => 'walking',
            'Hike' => 'hiking',
        ];
        return $map[$stravaType] ?? 'other';
    }
}
