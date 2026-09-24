<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Vérifiez votre email</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f0fdf4; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 40px auto; background: #fff; border-radius: 24px; padding: 30px;">
        <div style="text-align: center; font-size: 50px;">🏃</div>
        <h1 style="color: #22c55e; text-align: center;">Vérifiez votre email</h1>
        <p>Salut <strong>{{ $user->full_name }}</strong>,</p>
        <p>Merci de rejoindre Athletica ! Pour activer ton compte, clique sur le bouton ci-dessous :</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ config('app.url') }}/verify-email/{{ $token }}"
               style="display: inline-block; padding: 15px 30px; background: #22c55e; color: #fff; text-align: center; text-decoration: none; border-radius: 16px; font-weight: bold;">
                ✅ Vérifier mon email
            </a>
        </div>
        <p style="color: #94a3b8; font-size: 12px;">Si tu n'as pas créé de compte, ignore cet email.</p>
        <div style="text-align: center; color: #94a3b8; font-size: 12px; margin-top: 30px;">
            <p>© {{ date('Y') }} Athletica - Plateforme Sport Connecté</p>
            <p>Abidjan, Côte d'Ivoire 🇨🇮</p>
        </div>
    </div>
</body>
</html>
