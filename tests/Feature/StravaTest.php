<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StravaTest extends TestCase
{
    use RefreshDatabase;

    public function test_strava_redirect_works()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/connect/strava');

        // Doit rediriger vers Strava
        $response->assertRedirect();
        $this->assertStringContainsString(
            'https://www.strava.com/oauth/authorize',
            $response->getTargetUrl()
        );
    }

    public function test_strava_sync_route_exists()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/sync/strava');

        // Peut échouer (pas de token) mais la route doit exister
        $this->assertNotEquals(404, $response->getStatusCode());
    }

    public function test_strava_callback_handles_error()
    {
        $user = User::factory()->create();

        // Sans code, le callback doit rediriger avec erreur
        $response = $this->actingAs($user)->get('/connect/strava/callback');
        $response->assertRedirect();
    }

    public function test_strava_service_auth_url_is_valid()
    {
        $service = new \App\Services\StravaService();
        $url = $service->getAuthUrl();

        $this->assertStringContainsString('https://www.strava.com/oauth/authorize', $url);
        $this->assertStringContainsString('client_id=', $url);
        $this->assertStringContainsString('response_type=code', $url);
        $this->assertStringContainsString('redirect_uri=', $url);
    }
}