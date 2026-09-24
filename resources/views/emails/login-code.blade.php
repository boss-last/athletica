<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Code de connexion</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f0fdf4; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 40px auto; background: #fff; border-radius: 24px; padding: 30px; text-align: center;">
        <div style="font-size: 60px;">🏃</div>
        <h1 style="color: #22c55e;">Code de connexion</h1>

        <p>Salut <strong>{{ $user->full_name }}</strong>,</p>
        <p>Voici ton code de connexion à Athletica :</p>

        <div style="background: #22c55e; color: #fff; font-size: 42px; font-weight: bold; letter-spacing: 12px; padding: 25px; border-radius: 16px; margin: 30px 0;">
            {{ $code }}
        </div>

        <p style="color: #ef4444; font-size: 14px;">⚠️ Ce code expire dans 10 minutes.</p>
        <p style="color: #94a3b8; font-size: 12px;">Si tu n'as pas demandé ce code, ignore cet email.</p>

        <div style="color: #94a3b8; font-size: 12px; margin-top: 30px;">
            <p>© {{ date('Y') }} Athletica</p>
            <p>Abidjan, Côte d'Ivoire 🇨🇮</p>
        </div>
    </div>
</body>
</html>
