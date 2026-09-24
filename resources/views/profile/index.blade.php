@extends('layouts.app')

@section('title', 'Profil - Athletica')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1 space-y-6">
        <div class="glass-card rounded-3xl p-6 shadow-xl text-center">
            <img src="{{ ($user->avatar_url && str_contains($user->avatar_url, '/avatars/')) ? $user->avatar_url : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) . '&size=150&background=22c55e&color=fff' }}" class="w-32 h-32 rounded-full mx-auto border-4 border-green-500 mb-4">
            <h2 class="text-2xl font-bold text-slate-800">{{ $user->full_name }}</h2>
            <p class="text-slate-500">@{{ $user->username }}</p>
            @if($user->athleteProfile)
            <div class="mt-3 flex justify-center gap-2">
                <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700">{{ $user->athleteProfile->fitness_level }}</span>
                <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">{{ $user->athleteProfile->primary_sport }}</span>
            </div>
            @endif
            <form action="/profile/avatar" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf @method('PATCH')
                <label class="cursor-pointer rounded-full bg-slate-100 px-4 py-2 text-sm hover:bg-slate-200 transition inline-block">
                    <i class="fas fa-camera mr-1"></i>Changer la photo
                    <input type="file" name="avatar" class="hidden" onchange="this.form.submit()" accept="image/*">
                </label>
            </form>
            <div class="flex justify-center gap-6 mt-4 text-sm">
                <div><p class="font-bold text-xl">{{ $user->following->count() }}</p><p class="text-slate-400">Abonnements</p></div>
                <div><p class="font-bold text-xl">{{ $user->followers->count() }}</p><p class="text-slate-400">Abonnés</p></div>
            </div>
            @if($user->bio)<p class="text-slate-600 mt-4 text-sm">{{ $user->bio }}</p>@endif
        </div>

        <div class="glass-card rounded-3xl p-6 shadow-xl">
            <h3 class="font-bold text-lg mb-4">🏅 Badges</h3>
            @if($badges->count() > 0)
            <div class="grid grid-cols-3 gap-2">
                @foreach($badges as $badge)
                <div class="text-center p-2 bg-slate-50 rounded-xl"><span class="text-2xl">🏅</span><p class="text-xs text-slate-500 mt-1">{{ $badge->name }}</p></div>
                @endforeach
            </div>
            @else
            <p class="text-slate-400 text-sm text-center">Aucun badge</p>
            @endif
        </div>
    </div>

    <div class="md:col-span-2 space-y-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="glass-card rounded-3xl p-4 text-center shadow-lg"><p class="text-slate-400 text-sm">Activités</p><p class="text-3xl font-bold text-blue-600">{{ $totalStats->total_activities }}</p></div>
            <div class="glass-card rounded-3xl p-4 text-center shadow-lg"><p class="text-slate-400 text-sm">Distance</p><p class="text-3xl font-bold text-green-600">{{ number_format($totalStats->total_distance/1000, 1) }}<span class="text-sm">km</span></p></div>
            <div class="glass-card rounded-3xl p-4 text-center shadow-lg"><p class="text-slate-400 text-sm">Durée</p><p class="text-3xl font-bold text-yellow-600">{{ floor($totalStats->total_duration/3600) }}h</p></div>
            <div class="glass-card rounded-3xl p-4 text-center shadow-lg"><p class="text-slate-400 text-sm">Objectifs ✅</p><p class="text-3xl font-bold text-purple-600">{{ $completedGoals }}</p></div>
        </div>

        <div class="glass-card rounded-3xl p-6 shadow-xl">
            <h3 class="font-bold text-lg mb-4">🏆 Records</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-3 bg-orange-50 rounded-2xl"><i class="fas fa-road text-orange-500"></i><p class="text-sm text-slate-500">Course max</p><p class="font-bold">{{ $records['longest_run'] ? number_format($records['longest_run']/1000,1).' km' : '-' }}</p></div>
                <div class="text-center p-3 bg-red-50 rounded-2xl"><i class="fas fa-tachometer-alt text-red-500"></i><p class="text-sm text-slate-500">Allure max</p><p class="font-bold">{{ $records['fastest_pace'] ? number_format($records['fastest_pace'],1).' km/h' : '-' }}</p></div>
                <div class="text-center p-3 bg-yellow-50 rounded-2xl"><i class="fas fa-fire text-yellow-500"></i><p class="text-sm text-slate-500">Max calories</p><p class="font-bold">{{ $records['most_calories'] ?? '-' }}</p></div>
                <div class="text-center p-3 bg-blue-50 rounded-2xl"><i class="fas fa-clock text-blue-500"></i><p class="text-sm text-slate-500">Max durée</p><p class="font-bold">{{ $records['longest_duration'] ? gmdate('H:i', $records['longest_duration']) : '-' }}</p></div>
            </div>
        </div>

        <div class="glass-card rounded-3xl p-6 shadow-xl">
            <h3 class="font-bold text-lg mb-4">📊 Dernières Activités</h3>
            @foreach($recentActivities as $activity)
            <a href="/activities/{{ $activity->id }}" class="flex items-center justify-between py-3 border-b border-slate-100 last:border-b-0 hover:bg-slate-50 px-2 rounded-xl transition">
                <div class="flex items-center gap-3">
                    <span class="text-xl">@if($activity->sport_type=='running')🏃@elseif($activity->sport_type=='cycling')🚴@else🏅@endif</span>
                    <div><p class="font-medium">{{ $activity->sport_type }}</p><p class="text-sm text-slate-400">{{ $activity->start_time->format('d/m/Y') }}</p></div>
                </div>
                <div class="text-right"><p class="font-bold">{{ $activity->distance_km }} km</p><p class="text-sm text-slate-400">{{ gmdate('H:i:s', $activity->duration_seconds ?? 0) }}</p></div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
