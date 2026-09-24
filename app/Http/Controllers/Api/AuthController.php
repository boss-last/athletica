<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/register',
        tags: ['Authentication'],
        summary: 'Inscription utilisateur',
        description: 'Cree un nouveau compte utilisateur',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'username', 'full_name'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'newuser@athletica.io'),
                    new OA\Property(property: 'username', type: 'string', example: 'newuser'),
                    new OA\Property(property: 'full_name', type: 'string', example: 'New User'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Utilisateur cree',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string'),
                        new OA\Property(property: 'user', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Donnees invalides'),
        ]
    )]
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users',
            'full_name' => 'required|string|max:255',
        ]);

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'full_name' => $request->full_name,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            // Silencieux
        }

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    #[OA\Post(
        path: '/login',
        tags: ['Authentication'],
        summary: 'Connexion utilisateur',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'username'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@athletica.io'),
                    new OA\Property(property: 'username', type: 'string', example: 'john_doe'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Connexion reussie',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string'),
                        new OA\Property(property: 'user', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Identifiants incorrects'),
        ]
    )]
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'username' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
            ->where('username', $request->username)
            ->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Identifiants incorrects.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    #[OA\Get(
        path: '/profile',
        tags: ['Authentication'],
        summary: 'Profil de l utilisateur connecte',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Profil retourne'),
            new OA\Response(response: 401, description: 'Non authentifie'),
        ]
    )]
    public function profile(Request $request)
    {
        return response()->json($request->user()->load('athleteProfile'));
    }

    #[OA\Post(
        path: '/logout',
        tags: ['Authentication'],
        summary: 'Deconnexion',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Deconnecte avec succes'),
        ]
    )]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Deconnecte avec succes']);
    }
}
