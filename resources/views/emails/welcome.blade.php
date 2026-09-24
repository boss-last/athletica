<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bienvenue sur Athletica</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0fdf4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 24px; padding: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .logo { text-align: center; font-size: 50px; }
        h1 { color: #22c55e; text-align: center; }
        p { color: #334155; line-height: 1.6; }
        .btn { display: block; width: 200px; margin: 20px auto; padding: 15px; background: #22c55e; color: #fff; text-align: center; text-decoration: none; border-radius: 16px; font-weight: bold; }
        .footer { text-align: center; color: #94a3b8; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🏃</div>
        <h1>Bienvenue sur Athletica !</h1>
        <p>Salut <strong>{{ $user->full_name }}</strong>,</p>
        <p>Merci d'avoir rejoint <strong>Athletica</strong>, ta plateforme sport connecté ! 🎉</p>
        <p>Tu peux maintenant :</p>
        <ul>
            <li>📊 Suivre tes performances sportives</li>
            <li>🎯 Te fixer des objectifs</li>
            <li>🏆 Participer à des challenges</li>
            <li>🔗 Synchroniser tes activités Strava</li>
        </ul>
        <a href="{{ config('app.url') }}/dashboard" class="btn">Accéder au Dashboard</a>
        <div class="footer">
            <p>© {{ date('Y') }} Athletica - Plateforme Sport Connecté</p>
            <p>Abidjan, Côte d'Ivoire 🇨🇮</p>
        </div>
    </div>
</body>
</html>
