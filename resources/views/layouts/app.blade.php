<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Athletica')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.documentElement.classList.add('dark');
    }
</script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Leaflet (pour les cartes GPS) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Style personnalisé -->
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #f0fdf4 100%);
        }
    </style>
</head>
<body class="min-h-screen font-sans antialiased">
    <!-- Navigation -->
    <nav class="sticky top-0 z-50 border-b border-white/20 bg-green-600/95 backdrop-blur-md shadow-lg">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="/dashboard" class="text-2xl font-bold text-white transition hover:text-green-200">🏃 Athletica</a>
            <div class="flex items-center space-x-4">
                <a href="/activities" class="text-sm font-medium text-white/90 transition hover:text-white">
                    <i class="fas fa-list mr-1"></i> Activités
                </a>
                <a href="/goals" class="text-sm font-medium text-white/90 transition hover:text-white">
                    <i class="fas fa-bullseye mr-1"></i> Objectifs
                </a>
                <a href="/challenges" class="text-sm font-medium text-white/90 transition hover:text-white">
                    <i class="fas fa-trophy mr-1"></i> Challenges
                </a>
                <a href="/training" class="text-sm font-medium text-white/90 transition hover:text-white">
                    <i class="fas fa-dumbbell mr-1"></i> Entraînement
                </a>
                <a href="/fitness" class="text-sm font-medium text-white/90 transition hover:text-white">
                    <i class="fas fa-heartbeat mr-1"></i> Fitness
                </a>
                <a href="/badges" class="text-sm font-medium text-white/90 transition hover:text-white">
                    <i class="fas fa-medal mr-1"></i> Badges
                </a>
                <a href="/network" class="text-sm font-medium text-white/90 transition hover:text-white">
                    <i class="fas fa-users mr-1"></i> Réseau
                </a>
                 <!-- Bouton Premium -->
            @if(!auth()->user()->is_premium)
            <a href="/premium" class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white px-3 py-1.5 rounded-full text-xs font-bold hover:from-yellow-500 hover:to-yellow-700 transition">
                💎 Premium
            </a>
            @else
            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">💎 Premium</span>
            @endif
                <a href="/profile" class="flex items-center text-sm font-medium text-white/90 transition hover:text-white">
                    <span class="hidden sm:inline">{{ $user->full_name ?? 'Profil' }}</span>
                </a>
                <img src="{{ ($user->avatar_url ?? false) ? $user->avatar_url : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name ?? 'User') . '&background=22c55e&color=fff' }}"
                     class="h-10 w-10 rounded-full border-2 border-white">
                <form action="/logout" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-white/80 transition hover:text-red-200">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-6 py-4 text-green-700">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-6 py-4 text-red-700">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>
