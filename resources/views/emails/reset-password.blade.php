<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Réinitialisation de mot de passe</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f0fdf4; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 40px auto; background: #fff; border-radius: 24px; padding: 30px;">
        <div style="text-align: center; font-size: 50px;">🔒</div>
        <h1 style="color: #22c55e; text-align: center;">Réinitialisation de mot de passe</h1>
        <p>Salut <strong>{{ $user->full_name }}</strong>,</p>
        <p>Tu as demandé à réinitialiser ton mot de passe. Clique sur le bouton ci-dessous :</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}/reset-password/{{ $token }}"
               style="display: inline-block; padding: 15px 30px; background: #22c55e; color: #fff; text-decoration: none; border-radius: 16px; font-weight: bold;">
                🔑 Réinitialiser mon mot de passe
            </a>
        </div>
        <p style="color: #ef4444; font-size: 12px;">⚠️ Ce lien expire dans 1 heure.</p>
        <p style="color: #94a3b8; font-size: 12px;">Si tu n'as pas fait cette demande, ignore cet email.</p>
    </div>
</body>
</html>
