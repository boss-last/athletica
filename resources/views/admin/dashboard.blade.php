@extends('layouts.app')

@section('title', 'Admin - Athletica')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold">👑 Dashboard Admin</h1>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="glass-card rounded-3xl p-4 text-center">
            <p class="text-slate-400 text-xs">Utilisateurs</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['users'] }}</p>
        </div>
        <div class="glass-card rounded-3xl p-4 text-center">
            <p class="text-slate-400 text-xs">Actifs</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active_users'] }}</p>
        </div>
        <div class="glass-card rounded-3xl p-4 text-center">
            <p class="text-slate-400 text-xs">Premium</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['premium_users'] }}</p>
        </div>
        <div class="glass-card rounded-3xl p-4 text-center">
            <p class="text-slate-400 text-xs">Activités</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['activities'] }}</p>
        </div>
        <div class="glass-card rounded-3xl p-4 text-center">
            <p class="text-slate-400 text-xs">Challenges</p>
            <p class="text-2xl font-bold text-orange-600">{{ $stats['challenges'] }}</p>
        </div>
        <div class="glass-card rounded-3xl p-4 text-center">
            <p class="text-slate-400 text-xs">Badges</p>
            <p class="text-2xl font-bold text-pink-600">{{ $stats['badges'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="/admin/users" class="glass-card rounded-3xl p-5 text-center hover:shadow-xl transition">
            <span class="text-3xl">👥</span>
            <p class="font-bold mt-2">Utilisateurs</p>
        </a>
        <a href="/admin/badges" class="glass-card rounded-3xl p-5 text-center hover:shadow-xl transition">
            <span class="text-3xl">🏅</span>
            <p class="font-bold mt-2">Badges</p>
        </a>
        <a href="/admin/challenges" class="glass-card rounded-3xl p-5 text-center hover:shadow-xl transition">
            <span class="text-3xl">🏆</span>
            <p class="font-bold mt-2">Challenges</p>
        </a>
        <a href="/admin/notifications" class="glass-card rounded-3xl p-5 text-center hover:shadow-xl transition">
            <span class="text-3xl">🔔</span>
            <p class="font-bold mt-2">Notifications</p>
        </a>
    </div>

    <div class="glass-card rounded-3xl p-6">
        <h2 class="font-bold mb-4">📥 Exports</h2>
        <div class="flex gap-3">
            <a href="/admin/export/users" class="bg-blue-600 text-white px-5 py-2.5 rounded-2xl font-semibold hover:bg-blue-700">
                <i class="fas fa-file-csv mr-2"></i>Exporter utilisateurs
            </a>
            <a href="/admin/export/activities" class="bg-green-600 text-white px-5 py-2.5 rounded-2xl font-semibold hover:bg-green-700">
                <i class="fas fa-file-csv mr-2"></i>Exporter activités
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="glass-card rounded-3xl p-6">
            <h2 class="font-bold mb-4">👥 Derniers inscrits</h2>
            @foreach($recentUsers as $user)
            <div class="flex justify-between py-2 border-b last:border-b-0">
                <span class="text-sm">{{ $user->full_name }}</span>
                <span class="text-xs text-slate-400">{{ $user->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
        <div class="glass-card rounded-3xl p-6">
            <h2 class="font-bold mb-4">🏃 Dernières activités</h2>
            @foreach($recentActivities as $a)
            <div class="flex justify-between py-2 border-b last:border-b-0">
                <span class="text-sm">{{ $a->user->full_name ?? 'N/A' }} - {{ $a->sport_type }}</span>
                <span class="text-xs text-slate-400">{{ $a->start_time->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
