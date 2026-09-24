@extends('layouts.app')

@section('title', 'Dashboard - Athletica')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-green-600">Bienvenue</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Bonjour, {{ $user->full_name }} 👋</h1>
                <p class="mt-2 text-slate-600">Votre espace sportif en un seul endroit, avec des indicateurs clairs et un design premium.</p>
            </div>
            <div class="rounded-2xl bg-green-50 px-4 py-3 text-sm text-green-700">
                <i class="fas fa-fire mr-2"></i> Progression hebdo • {{ $weeklyStats ? 'à jour' : 'en attente' }}
            </div>
        </div>
    </div>

    @php $stravaConnected = auth()->user()->platformConnections()->where('platform_name', 'strava')->exists(); @endphp
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <div class="rounded-2xl bg-orange-100 p-3 text-2xl">🔗</div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Intégration Strava</h2>
                    <p class="text-sm text-slate-500">Reliez votre compte pour synchroniser vos activités.</p>
                </div>
            </div>
            @if($stravaConnected)
                <div class="flex items-center gap-3">
                    <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700">✅ Connecté</span>
                    <form action="/sync/strava" method="POST">
                        @csrf
                        <button type="submit" class="rounded-full bg-orange-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-orange-600">🔄 Synchroniser</button>
                    </form>
                </div>
            @else
                <a href="/connect/strava" class="rounded-full bg-orange-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-600">🔗 Connecter Strava</a>
            @endif
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="glass-card rounded-3xl p-5 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="rounded-2xl bg-blue-100 p-3 text-blue-600"><i class="fas fa-running"></i></div>
                <div>
                    <p class="text-sm text-slate-500">Activités</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $totalStats->total_activities }}</p>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-3xl p-5 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="rounded-2xl bg-green-100 p-3 text-green-600"><i class="fas fa-road"></i></div>
                <div>
                    <p class="text-sm text-slate-500">Distance</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($totalStats->total_distance / 1000, 1) }} km</p>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-3xl p-5 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="rounded-2xl bg-yellow-100 p-3 text-yellow-600"><i class="fas fa-clock"></i></div>
                <div>
                    <p class="text-sm text-slate-500">Temps</p>
                    <p class="text-2xl font-bold text-slate-900">{{ floor($totalStats->total_duration / 3600) }}h {{ floor(($totalStats->total_duration % 3600) / 60) }}min</p>
                </div>
            </div>
        </div>
        <div class="glass-card rounded-3xl p-5 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="rounded-2xl bg-red-100 p-3 text-red-600"><i class="fas fa-fire"></i></div>
                <div>
                    <p class="text-sm text-slate-500">Calories</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($totalStats->total_calories) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="glass-card rounded-3xl p-6 shadow-xl xl:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">📊 Répartition par sport</h2>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-500">Vue globale</span>
            </div>
            @if($sportsBreakdown->count() > 0)
                <canvas id="sportsChart" height="220"></canvas>
            @else
                <p class="py-10 text-center text-slate-500">Aucune activité pour le moment</p>
            @endif
        </div>

        <div class="glass-card rounded-3xl p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">🎯 Objectifs</h2>
                <a href="/goals" class="text-sm font-semibold text-green-600">Voir tout</a>
            </div>
            @forelse($activeGoals as $goal)
                <div class="mb-4 border-b border-slate-100 pb-4 last:border-b-0 last:pb-0">
                    <div class="mb-2 flex items-center justify-between text-sm">
                        <span class="font-semibold text-slate-700">{{ $goal->title }}</span>
                        <span class="font-bold text-green-600">{{ $goal->progress }}%</span>
                    </div>
                    <div class="h-2.5 rounded-full bg-slate-100">
                        <div class="h-2.5 rounded-full bg-green-500" style="width: {{ $goal->progress }}%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">{{ $goal->sport_type ?? 'Tous' }} · {{ number_format($goal->current_value) }}/{{ number_format($goal->target_value) }}</p>
                </div>
            @empty
                <div class="py-8 text-center text-slate-500">
                    <p>Aucun objectif en cours</p>
                    <a href="/goals/create" class="mt-2 inline-block text-sm font-semibold text-green-600">Créer un objectif</a>
                </div>
            @endforelse
        </div>
    </div>

    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-slate-900">📋 Activités récentes</h2>
            <div class="flex gap-3">
                <a href="/activities/create" class="rounded-full bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700">+ Nouvelle</a>
                <a href="/activities" class="text-sm font-semibold text-green-600">Voir tout</a>
            </div>
        </div>

        @if($recentActivities->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-left text-slate-500">
                            <th class="px-3 py-3">Date</th>
                            <th class="px-3 py-3">Sport</th>
                            <th class="px-3 py-3 text-right">Distance</th>
                            <th class="px-3 py-3 text-right">Durée</th>
                            <th class="px-3 py-3 text-right">FC</th>
                            <th class="px-3 py-3 text-right">Calories</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentActivities as $activity)
                            <tr class="cursor-pointer border-b border-slate-50 transition hover:bg-green-50" onclick="window.location='/activities/{{ $activity->id }}'">
                                <td class="px-3 py-3">{{ $activity->start_time->format('d/m/Y') }}</td>
                                <td class="px-3 py-3">
                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                                        @if($activity->sport_type == 'running') 🏃
                                        @elseif($activity->sport_type == 'cycling') 🚴
                                        @elseif($activity->sport_type == 'swimming') 🏊
                                        @elseif($activity->sport_type == 'walking') 🚶
                                        @else 🏅
                                        @endif
                                        {{ $activity->sport_type }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-right font-semibold">{{ $activity->distance_km }} km</td>
                                <td class="px-3 py-3 text-right">{{ gmdate('H:i:s', $activity->duration_seconds ?? 0) }}</td>
                                <td class="px-3 py-3 text-right">{{ $activity->avg_heart_rate ?? '-' }} bpm</td>
                                <td class="px-3 py-3 text-right">{{ $activity->calories_burned ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-8 text-center text-slate-500">
                <p>Aucune activité pour le moment</p>
                <a href="/activities/create" class="mt-2 inline-block text-sm font-semibold text-green-600">Ajouter une activité</a>
            </div>
        @endif
    </div>
</div>

@if($sportsBreakdown->count() > 0)
<script>
    const ctx = document.getElementById('sportsChart').getContext('2d');
    const sportsData = @json($sportsBreakdown);
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: sportsData.map(s => s.sport_type),
            datasets: [{
                data: sportsData.map(s => s.total_distance),
                backgroundColor: ['#22c55e', '#3b82f6', '#eab308', '#ef4444', '#8b5cf6', '#ec4899'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } }
            }
        }
    });
</script>
@endif
@endsection
