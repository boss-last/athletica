<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\ChallengeController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Activités
    Route::apiResource('activities', ActivityController::class);
    Route::get('/stats', [ActivityController::class, 'stats']);

    // Objectifs
    Route::apiResource('goals', GoalController::class)->except(['update']);

    // Challenges
    Route::get('/challenges', [ChallengeController::class, 'index']);
    Route::post('/challenges', [ChallengeController::class, 'store']);
    Route::post('/challenges/{id}/join', [ChallengeController::class, 'join']);
});
