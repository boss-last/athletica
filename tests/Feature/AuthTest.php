<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_via_api()
    {
        $user = User::create([
            'email' => 'test@athletica.io',
            'username' => 'testuser',
            'full_name' => 'Test User',
            'password' => bcrypt('test123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@athletica.io',
            'username' => 'testuser',
            'password' => 'test123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'email', 'username', 'full_name']
            ]);
    }

    public function test_user_can_register_via_api()
    {
        $response = $this->postJson('/api/register', [
            'email' => 'newuser@athletica.io',
            'username' => 'newuser',
            'full_name' => 'New User',
            'password' => 'newuser123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'email', 'username', 'full_name']
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@athletica.io',
            'username' => 'newuser',
        ]);
    }

    public function test_login_fails_with_wrong_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@email.com',
            'username' => 'wronguser',
        ]);

        $response->assertStatus(422);
    }

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
{
    $user = new User();
    $user->email = 'auth@athletica.io';
    $user->username = 'authuser';
    $user->full_name = 'Auth User';
    $user->password = bcrypt('auth123');
    $user->save();

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(200); // Accepter 200 ou 500 selon les tables
}
}
