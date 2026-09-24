@extends('layouts.app')

@section('title', 'Challenges - Athletica')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl flex items-center justify-between">
        <h1 class="text-3xl font-bold text-slate-900">🏆 Challenges</h1>
        <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>Créer un challenge
        </button>
    </div>

    @if($myChallenges->count() > 0)
    <h2 class="text-xl font-bold text-slate-800">📌 Mes Challenges</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($myChallenges as $pc)
        <a href="/challenges/{{ $pc->challenge->id }}" class="glass-card rounded-3xl p-6 shadow-xl hover:shadow-2xl transition block">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-bold text-lg">{{ $pc->challenge->title }}</h3>
                    <p class="text-sm text-slate-500">par {{ $pc->challenge->creator->full_name }}</p>
                </div>
                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">{{ $pc->challenge->challenge_type }}</span>
            </div>
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-1"><span>Progression</span><span class="font-bold">{{ number_format($pc->current_progress) }}/{{ number_format($pc->challenge->target_value) }}</span></div>
                <div class="h-2 rounded-full bg-slate-100"><div class="h-2 rounded-full bg-purple-500" style="width: {{ $pc->challenge->target_value > 0 ? ($pc->current_progress/$pc->challenge->target_value)*100 : 0 }}%"></div></div>
            </div>
        </a>
        @endforeach
    </div>
    @endif

    <h2 class="text-xl font-bold text-slate-800">🌍 Challenges Publics</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($challenges as $challenge)
        <div class="glass-card rounded-3xl p-6 shadow-xl">
            <div class="flex items-start justify-between mb-3">
                <h3 class="font-bold">{{ $challenge->title }}</h3>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs">{{ $challenge->challenge_type }}</span>
            </div>
            <p class="text-sm text-slate-500 mb-3">{{ Str::limit($challenge->description, 80) }}</p>
            <div class="flex items-center gap-3 text-sm text-slate-400 mb-3">
                <span>🎯 {{ number_format($challenge->target_value) }}</span>
                @if($challenge->sport_type)<span>🏅 {{ $challenge->sport_type }}</span>@endif
            </div>
            <div class="flex justify-between text-xs text-slate-400 mb-4">
                <span>{{ $challenge->start_date->format('d/m') }} - {{ $challenge->end_date->format('d/m') }}</span>
                <span>{{ $challenge->participants_count }} 👥</span>
            </div>
            <div class="flex gap-2">
                <a href="/challenges/{{ $challenge->id }}" class="flex-1 text-center py-2 border border-slate-200 rounded-xl text-sm hover:bg-slate-50 transition">Classement</a>
                @if(!$challenge->participants->contains(auth()->id()))
                <form action="/challenges/{{ $challenge->id }}/join" method="POST" class="flex-1">
                    @csrf
                    <button class="w-full py-2 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition">Participer</button>
                </form>
                @else
                <span class="flex-1 text-center py-2 bg-green-100 text-green-700 rounded-xl text-sm font-semibold">✅ Inscrit</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal Création -->
<div id="createModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="glass-card rounded-3xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">🏆 Nouveau Challenge</h2>
            <button onclick="document.getElementById('createModal').classList.add('hidden')" class="text-slate-400 text-xl">&times;</button>
        </div>
        <form action="/challenges" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="title" required class="w-full px-3 py-2 border rounded-xl text-sm" placeholder="Titre">
            <textarea name="description" rows="2" class="w-full px-3 py-2 border rounded-xl text-sm" placeholder="Description"></textarea>
            <div class="grid grid-cols-2 gap-3">
                <select name="challenge_type" required class="px-3 py-2 border rounded-xl text-sm"><option value="distance">📏 Distance</option><option value="duration">⏱️ Durée</option><option value="frequency">🔄 Fréquence</option></select>
                <input type="number" name="target_value" required class="px-3 py-2 border rounded-xl text-sm" placeholder="Objectif">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <input type="date" name="start_date" required class="px-3 py-2 border rounded-xl text-sm">
                <input type="date" name="end_date" required class="px-3 py-2 border rounded-xl text-sm">
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-xl font-semibold hover:bg-green-700 transition">Créer</button>
        </form>
    </div>
</div>
@endsection
