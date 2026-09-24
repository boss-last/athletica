<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouvelle connexion</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f0fdf4; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 40px auto; background: #fff; border-radius: 24px; padding: 30px;">
        <div style="text-align: center; font-size: 50px;">🔐</div>
        <h1 style="color: #22c55e; text-align: center;">Nouvelle connexion</h1>

        <p>Salut <strong>{{ $user->full_name }}</strong>,</p>
        <p>Nous avons détecté une nouvelle connexion à votre compte Athletica.</p>

        <div style="background: #f8fafc; border-radius: 16px; padding: 20px; margin: 20px 0;">
            <h3 style="color: #334155; margin-top: 0;">📋 Détails de la connexion</h3>
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <td style="padding: 8px 0; color: #64748b;">🕐 Date et heure</td>
                    <td style="padding: 8px 0; font-weight: bold;">{{ $loginTime }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #64748b;">🌐 Adresse IP</td>
                    <td style="padding: 8px 0; font-weight: bold;">{{ $ipAddress }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #64748b;">💻 Appareil</td>
                    <td style="padding: 8px 0; font-weight: bold; font-size: 12px;">{{ \Str::limit($userAgent, 60) }}</td>
                </tr>
            </table>
        </div>

        <p style="background: #fef3c7; padding: 15px; border-radius: 12px; color: #92400e; font-size: 14px;">
            ⚠️ <strong>Ce n'était pas vous ?</strong><br>
            Si vous n'êtes pas à l'origine de cette connexion, changez immédiatement votre mot de passe.
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}/forgot-password"
               style="display: inline-block; padding: 12px 25px; background: #22c55e; color: #fff; text-decoration: none; border-radius: 12px; font-weight: bold;">
                🔑 Changer mon mot de passe
            </a>
        </div>

        <div style="text-align: center; color: #94a3b8; font-size: 12px; margin-top: 30px;">
            <p>© {{ date('Y') }} Athletica - Plateforme Sport Connecté</p>
            <p>Abidjan, Côte d'Ivoire 🇨🇮</p>
        </div>
    </div>
</body>
</html>
