<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Rapport Hebdomadaire Athletica</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0fdf4; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 24px; padding: 30px; }
        h1 { color: #22c55e; }
        .stats { display: flex; justify-content: space-around; margin: 20px 0; }
        .stat { text-align: center; padding: 15px; background: #f8fafc; border-radius: 16px; }
        .stat-value { font-size: 28px; font-weight: bold; color: #22c55e; }
        .stat-label { font-size: 12px; color: #64748b; }
        .btn { display: block; width: 220px; margin: 20px auto; padding: 15px; background: #22c55e; color: #fff; text-align: center; text-decoration: none; border-radius: 16px; font-weight: bold; }
        .footer { text-align: center; color: #94a3b8; font-size: 12px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏃 Rapport Hebdomadaire</h1>
        <p>Salut <strong>{{ $user->full_name }}</strong>, voici ton récap de la semaine !</p>

        <div class="stats">
            <div class="stat">
                <div class="stat-value">{{ $stats->total ?? 0 }}</div>
                <div class="stat-label">Activités</div>
            </div>
            <div class="stat">
                <div class="stat-value">{{ number_format(($stats->total_distance ?? 0) / 1000, 1) }} km</div>
                <div class="stat-label">Distance</div>
            </div>
            <div class="stat">
                <div class="stat-value">{{ floor(($stats->total_duration ?? 0) / 60) }}h</div>
                <div class="stat-label">Durée</div>
            </div>
        </div>

        <a href="{{ config('app.url') }}/dashboard" class="btn">Voir mon Dashboard</a>

        <p>Continue comme ça ! 💪</p>
        <div class="footer">
            <p>© {{ date('Y') }} Athletica</p>
        </div>
    </div>
</body>
</html>
