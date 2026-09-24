<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\StravaService;
use Illuminate\Support\Facades\Http;

class StravaServiceTest extends TestCase
{
    public function test_get_auth_url_returns_valid_strava_url()
    {
        $service = new StravaService();

        $url = $service->getAuthUrl();

        $this->assertStringContainsString('https://www.strava.com/oauth/authorize', $url);
        $this->assertStringContainsString('client_id=', $url);
        $this->assertStringContainsString('response_type=code', $url);
    }

    public function test_get_token_exchanges_code_for_token()
    {
        Http::fake([
            'https://www.strava.com/oauth/token' => Http::response([
                'access_token' => 'fake_access_token',
                'refresh_token' => 'fake_refresh_token',
                'expires_at' => now()->addHours(6)->timestamp,
                'athlete' => [
                    'id' => 12345,
                    'firstname' => 'Test',
                    'lastname' => 'Athlete',
                ],
            ], 200),
        ]);

        $service = new StravaService();
        $result = $service->getToken('fake_code');

        $this->assertArrayHasKey('access_token', $result);
        $this->assertEquals('fake_access_token', $result['access_token']);
    }

    public function test_map_sport_type_returns_correct_mapping()
    {
        $service = new StravaService();

        $this->assertEquals('running', $this->invokeMethod($service, 'mapSportType', ['Run']));
        $this->assertEquals('cycling', $this->invokeMethod($service, 'mapSportType', ['Ride']));
        $this->assertEquals('swimming', $this->invokeMethod($service, 'mapSportType', ['Swim']));
        $this->assertEquals('other', $this->invokeMethod($service, 'mapSportType', ['Unknown']));
    }

    /**
     * Helper pour tester les méthodes privées
     */
    private function invokeMethod($object, string $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}
