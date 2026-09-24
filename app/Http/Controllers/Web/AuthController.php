<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeMail;
use App\Mail\VerifyEmailMail;
use App\Mail\ResetPasswordMail;
use App\Mail\LoginCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ==========================================
    // LOGIN / REGISTER
    // ==========================================
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Étape 1 : l'utilisateur entre email + username
     * → On génère un code à 6 chiffres et on l'envoie par email
     */
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
            return back()->withErrors(['email' => 'Identifiants incorrects.'])->withInput();
        }

        // Générer un code à 6 chiffres
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->login_code = $code;
        $user->login_code_expires_at = now()->addMinutes(10);
        $user->save();

        // Envoyer le code par email
        try {
            Mail::to($user->email)->send(new LoginCodeMail($user, $code));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Erreur d\'envoi du code : ' . $e->getMessage()]);
        }

        // Sauvegarder l'utilisateur en session temporaire
        session(['login_user_id' => $user->id]);

        return redirect('/login/verify')->with('success', '📧 Un code à 6 chiffres a été envoyé à votre email.');
    }

    /**
     * Étape 2 : Afficher le formulaire de saisie du code
     */
    public function showVerifyCode()
    {
        if (!session('login_user_id')) {
            return redirect('/login');
        }
        return view('auth.verify-code');
    }

    /**
     * Étape 3 : Vérifier le code et connecter l'utilisateur
     */
    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $userId = session('login_user_id');
        if (!$userId) {
            return redirect('/login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect('/login');
        }

        if ($user->login_code !== $request->code) {
            return back()->withErrors(['code' => 'Code incorrect.']);
        }

        if ($user->login_code_expires_at < now()) {
            return back()->withErrors(['code' => 'Code expiré. Demandez-en un nouveau.']);
        }

        // Code valide → connecter
        $user->login_code = null;
        $user->login_code_expires_at = null;
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();
        session()->forget('login_user_id');

        return redirect('/dashboard')->with('success', '✅ Connexion réussie !');
    }

    // ==========================================
    // INSCRIPTION
    // ==========================================
    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'full_name' => 'required|string|max:255',
        ]);

        $user = new User();
        $user->email = $data['email'];
        $user->username = $data['username'];
        $user->full_name = $data['full_name'];
        $user->role = 'user';
        $user->is_active = true;
        $user->verification_token = Str::random(64);
        $user->save();

        // Envoyer emails
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
            Mail::to($user->email)->send(new VerifyEmailMail($user, $user->verification_token));
        } catch (\Exception $e) {
            // Silencieux
        }

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Bienvenue ! Vérifiez votre email pour activer toutes les fonctionnalités.');
    }

    // ==========================================
    // VÉRIFICATION EMAIL
    // ==========================================
    public function verifyEmail($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect('/login')->with('error', 'Lien de vérification invalide ou expiré.');
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return redirect('/dashboard')->with('success', '✅ Email vérifié avec succès !');
    }

    // ==========================================
    // MOT DE PASSE OUBLIÉ
    // ==========================================
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Aucun compte trouvé avec cet email.');
        }

        $user->reset_token = Str::random(64);
        $user->reset_token_expires_at = now()->addHour();
        $user->save();

        try {
            Mail::to($user->email)->send(new ResetPasswordMail($user, $user->reset_token));
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur d\'envoi d\'email.');
        }

        return back()->with('success', '📧 Un lien de réinitialisation a été envoyé à votre email.');
    }

    public function showResetPassword($token)
    {
        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('reset_token', $request->token)
            ->where('reset_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return redirect('/login')->with('error', 'Lien expiré ou invalide.');
        }

        $user->password = bcrypt($request->password);
        $user->reset_token = null;
        $user->reset_token_expires_at = null;
        $user->save();

        return redirect('/login')->with('success', '🔑 Mot de passe réinitialisé ! Connectez-vous.');
    }

    // ==========================================
    // DÉCONNEXION
    // ==========================================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
