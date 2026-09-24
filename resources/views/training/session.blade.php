@extends('layouts.app')

@section('title', 'Séance - Athletica')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $session->title }}</h1>
                <p class="text-slate-500">Semaine {{ $session->week_number }} - Jour {{ $session->day_number }}</p>
            </div>
            <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700">{{ $session->sport_type }}</span>
        </div>
    </div>

    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h2 class="text-lg font-bold mb-4">📝 Description</h2>
        <p class="text-slate-600">{{ $session->description ?? 'Aucune description' }}</p>
        @if($session->notes)
        <div class="mt-4 rounded-2xl bg-yellow-50 p-3"><p class="text-sm text-yellow-800">📌 {{ $session->notes }}</p></div>
        @endif
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @if($session->planned_duration)
        <div class="glass-card rounded-3xl p-4 text-center shadow-lg"><i class="fas fa-clock text-blue-500 text-xl"></i><p class="text-2xl font-bold mt-1">{{ $session->planned_duration }}</p><p class="text-xs text-slate-400">minutes</p></div>
        @endif
        @if($session->planned_distance)
        <div class="glass-card rounded-3xl p-4 text-center shadow-lg"><i class="fas fa-road text-green-500 text-xl"></i><p class="text-2xl font-bold mt-1">{{ number_format($session->planned_distance/1000, 1) }}</p><p class="text-xs text-slate-400">km</p></div>
        @endif
        @if($session->intensity)
        <div class="glass-card rounded-3xl p-4 text-center shadow-lg"><i class="fas fa-tachometer-alt text-red-500 text-xl"></i><p class="text-2xl font-bold mt-1">{{ $session->intensity }}</p><p class="text-xs text-slate-400">intensité</p></div>
        @endif
        <div class="glass-card rounded-3xl p-4 text-center shadow-lg"><i class="fas fa-calendar text-purple-500 text-xl"></i><p class="text-2xl font-bold mt-1">S{{ $session->week_number }}-J{{ $session->day_number }}</p><p class="text-xs text-slate-400">Semaine-Jour</p></div>
    </div>

    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h2 class="text-lg font-bold mb-2">📋 Plan d'entraînement</h2>
        <a href="/training/{{ $session->plan->id }}" class="text-green-600 font-semibold hover:underline">{{ $session->plan->name }}</a>
    </div>
</div>
@endsection
