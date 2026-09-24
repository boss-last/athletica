<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Activity;
use App\Models\Badge;
use App\Models\Challenge;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==========================================
    // DASHBOARD
    // ==========================================
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'premium_users' => User::where('is_premium', 'true')->count(),
            'activities' => Activity::count(),
            'challenges' => Challenge::count(),
            'badges' => Badge::count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentActivities = Activity::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentActivities'));
    }

    // ==========================================
    // UTILISATEURS
    // ==========================================
    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users-create');
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'full_name' => 'required|string|max:255',
            'role' => 'required|in:user,coach,admin',
            'is_active' => 'boolean',
        ]);

        $user = new User();
        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->full_name = $data['full_name'];
        $user->role = $data['role'];
        $user->is_active = $request->has('is_active');
        $user->save();

        return redirect('/admin/users')->with('success', 'Utilisateur créé !');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users-edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'username' => 'required|string|unique:users,username,' . $id,
            'full_name' => 'required|string|max:255',
            'role' => 'required|in:user,coach,admin',
        ]);

        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->full_name = $data['full_name'];
        $user->role = $data['role'];
        $user->is_active = $request->has('is_active');
        $user->is_premium = $request->has('is_premium');
        $user->save();

        return redirect('/admin/users')->with('success', 'Utilisateur modifié !');
    }

    public function toggleUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Statut modifié !');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }

    // ==========================================
    // BADGES
    // ==========================================
    public function badges()
    {
        $badges = Badge::all();
        return view('admin.badges', compact('badges'));
    }

    public function createBadge()
    {
        return view('admin.badges-create');
    }

    public function storeBadge(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'points' => 'required|integer|min:0',
        ]);

        Badge::create($data);
        return redirect('/admin/badges')->with('success', 'Badge créé !');
    }

    public function deleteBadge($id)
    {
        Badge::findOrFail($id)->delete();
        return back()->with('success', 'Badge supprimé.');
    }

    // ==========================================
    // CHALLENGES
    // ==========================================
    public function challenges()
    {
        $challenges = Challenge::with('creator')->latest()->get();
        return view('admin.challenges', compact('challenges'));
    }

    public function storeChallenge(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'challenge_type' => 'required|string',
            'target_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $challenge = new Challenge();
        $challenge->creator_id = auth()->id();
        $challenge->title = $data['title'];
        $challenge->description = $data['description'];
        $challenge->challenge_type = $data['challenge_type'];
        $challenge->target_value = $data['target_value'];
        $challenge->start_date = $data['start_date'];
        $challenge->end_date = $data['end_date'];
        $challenge->is_public = true;
        $challenge->save();

        return redirect('/admin/challenges')->with('success', 'Challenge créé !');
    }

    public function deleteChallenge($id)
    {
        Challenge::findOrFail($id)->delete();
        return back()->with('success', 'Challenge supprimé.');
    }

    // ==========================================
    // NOTIFICATIONS GROUPÉES
    // ==========================================
    public function notifications()
    {
        return view('admin.notifications');
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'target' => 'required|in:all,premium,active',
        ]);

        $query = User::query();
        if ($request->target === 'premium') $query->where('is_premium', true);
        if ($request->target === 'active') $query->where('is_active', true);

        $users = $query->get();

        foreach ($users as $user) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $user->id,
                'type' => 'App\Notifications\AdminNotification',
                'data' => json_encode([
                    'title' => $request->title,
                    'message' => $request->message,
                ]),
            ]);
        }

        return back()->with('success', $users->count() . ' notifications envoyées !');
    }

    // ==========================================
    // EXPORT CSV
    // ==========================================
    public function exportUsers()
    {
        $users = User::all();
        $filename = 'users_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Email', 'Username', 'Nom', 'Rôle', 'Premium', 'Actif', 'Créé le']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->email,
                    $user->username,
                    $user->full_name,
                    $user->role,
                    $user->is_premium ? 'Oui' : 'Non',
                    $user->is_active ? 'Oui' : 'Non',
                    $user->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportActivities()
    {
        $activities = Activity::with('user')->get();
        $filename = 'activities_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($activities) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Utilisateur', 'Sport', 'Distance (km)', 'Durée (s)', 'Calories', 'Date']);

            foreach ($activities as $a) {
                fputcsv($file, [
                    $a->id,
                    $a->user->full_name ?? 'N/A',
                    $a->sport_type,
                    $a->distance_km,
                    $a->duration_seconds,
                    $a->calories_burned,
                    $a->start_time->format('d/m/Y H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
