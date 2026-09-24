<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ActivityController;
use App\Http\Controllers\Web\GoalController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ChallengeController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\FollowController;
use App\Http\Controllers\Web\StravaController;
use App\Http\Controllers\Web\SocialController;
use App\Http\Controllers\Web\BadgeController;
use App\Http\Controllers\Web\TrainingController;
use App\Http\Controllers\Web\FitnessController;
use App\Http\Controllers\Web\PremiumController;
use App\Http\Controllers\Web\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ==========================================
// REDIRECTION ACCUEIL
// ==========================================
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});

// ==========================================
// AUTHENTIFICATION (publique)
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Vérification email
Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');

// Mot de passe oublié
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/login/verify', [AuthController::class, 'showVerifyCode'])->name('login.verify');
Route::post('/login/verify', [AuthController::class, 'verifyCode'])->name('login.verify.post');

// ==========================================
// ROUTES PROTÉGÉES
// ==========================================
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    // Activités
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::post('/activities/{id}/gps', [ActivityController::class, 'importGps'])->name('activities.gps');
    Route::post('/activities/{id}/like', [ActivityController::class, 'toggleLike'])->name('activities.like');
    Route::post('/activities/{id}/comment', [ActivityController::class, 'addComment'])->name('activities.comment');

    // Activité détail (PREMIUM)
    Route::middleware('premium')->group(function () {
        Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');
    });

    // Objectifs
    Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
    Route::get('/goals/create', [GoalController::class, 'create'])->name('goals.create');
    Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
    Route::patch('/goals/{id}/progress', [GoalController::class, 'updateProgress'])->name('goals.progress');
    Route::delete('/goals/{id}', [GoalController::class, 'destroy'])->name('goals.destroy');

    // Challenges
    Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
    Route::post('/challenges', [ChallengeController::class, 'store'])->name('challenges.store');
    Route::get('/challenges/{id}', [ChallengeController::class, 'show'])->name('challenges.show');
    Route::post('/challenges/{id}/join', [ChallengeController::class, 'join'])->name('challenges.join');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Social
    Route::get('/network', [SocialController::class, 'network'])->name('network');
    Route::get('/connections', [SocialController::class, 'connections'])->name('connections');
    Route::post('/follow/{id}', [FollowController::class, 'toggle'])->name('follow.toggle');

    // Badges
    Route::get('/badges', [BadgeController::class, 'index'])->name('badges.index');

    // Training
    Route::get('/training', [TrainingController::class, 'index'])->name('training.index');
    Route::get('/training/{id}', [TrainingController::class, 'show'])->name('training.show');
    Route::get('/training/session/{id}', [TrainingController::class, 'session'])->name('training.session');

    // Fitness
    Route::get('/fitness', [FitnessController::class, 'index'])->name('fitness.index');
    Route::post('/fitness', [FitnessController::class, 'store'])->name('fitness.store');

    // Premium (Stripe)
    Route::get('/premium', [PremiumController::class, 'index'])->name('premium.index');
    Route::get('/premium/subscribe/{plan}', [PremiumController::class, 'subscribe'])->name('premium.subscribe');
    Route::get('/premium/success', [PremiumController::class, 'success'])->name('premium.success');
    Route::get('/premium/cancel', [PremiumController::class, 'cancel'])->name('premium.cancel');

    // Strava (PREMIUM)
    Route::middleware('premium')->group(function () {
        Route::get('/connect/strava', [StravaController::class, 'redirect'])->name('connect.strava');
        Route::get('/connect/strava/callback', [StravaController::class, 'callback'])->name('strava.callback');
        Route::post('/sync/strava', [StravaController::class, 'sync'])->name('sync.strava');
     });

});

// ==========================================
// WEBHOOK STRIPE (hors auth + hors CSRF)
// ==========================================
Route::post('/stripe/webhook', [PremiumController::class, 'webhook'])
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// ==========================================
// ADMIN (hors auth + hors CSRF)
// ==========================================
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Utilisateurs
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::patch('/users/{id}/toggle', [AdminController::class, 'toggleUser'])->name('admin.users.toggle');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // Badges
    Route::get('/badges', [AdminController::class, 'badges'])->name('admin.badges');
    Route::get('/badges/create', [AdminController::class, 'createBadge'])->name('admin.badges.create');
    Route::post('/badges', [AdminController::class, 'storeBadge'])->name('admin.badges.store');
    Route::delete('/badges/{id}', [AdminController::class, 'deleteBadge'])->name('admin.badges.delete');

    // Challenges
    Route::get('/challenges', [AdminController::class, 'challenges'])->name('admin.challenges');
    Route::post('/challenges', [AdminController::class, 'storeChallenge'])->name('admin.challenges.store');
    Route::delete('/challenges/{id}', [AdminController::class, 'deleteChallenge'])->name('admin.challenges.delete');

    // Notifications groupées
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('admin.notifications');
    Route::post('/notifications/send', [AdminController::class, 'sendNotification'])->name('admin.notifications.send');

    // Export CSV
    Route::get('/export/users', [AdminController::class, 'exportUsers'])->name('admin.export.users');
    Route::get('/export/activities', [AdminController::class, 'exportActivities'])->name('admin.export.activities');
});

