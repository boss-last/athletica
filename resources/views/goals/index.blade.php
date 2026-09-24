@extends('layouts.app')

@section('title', 'Objectifs - Athletica')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl flex items-center justify-between">
        <h1 class="text-3xl font-bold text-slate-900">🎯 Mes Objectifs</h1>
        <a href="/goals/create" class="rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>Nouvel objectif
        </a>
    </div>

    @if($goals->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($goals as $goal)
        <div class="glass-card rounded-3xl p-6 shadow-xl {{ $goal->is_completed ? 'border-2 border-green-500' : '' }}">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-800">{{ $goal->title }}</h3>
                    @if($goal->sport_type)<span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ $goal->sport_type }}</span>@endif
                </div>
                <div class="flex gap-2">
                    @if($goal->is_completed)<span class="text-2xl">✅</span>@endif
                    <form action="/goals/{{ $goal->id }}" method="POST" onsubmit="return confirm('Supprimer ?')">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            <div class="mb-3">
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium">{{ number_format($goal->current_value) }} / {{ number_format($goal->target_value) }}</span>
                    <span class="font-bold text-green-600">{{ $goal->progress }}%</span>
                </div>
                <div class="h-3 rounded-full bg-slate-100"><div class="h-3 rounded-full bg-green-500" style="width: {{ $goal->progress }}%"></div></div>
            </div>
            <div class="flex justify-between text-xs text-slate-400 mb-4">
                <span>{{ $goal->start_date->format('d/m/Y') }} - {{ $goal->end_date->format('d/m/Y') }}</span>
            </div>
            @if(!$goal->is_completed)
            <form action="/goals/{{ $goal->id }}/progress" method="POST" class="flex gap-2">
                @csrf @method('PATCH')
                <input type="number" name="current_value" value="{{ $goal->current_value }}" step="0.01" class="flex-1 px-3 py-2 border rounded-xl text-sm">
                <button class="bg-green-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-green-700">Mettre à jour</button>
            </form>
            @else
            <p class="text-center text-green-600 font-semibold">🎉 Atteint le {{ $goal->completed_at->format('d/m/Y') }} !</p>
            @endif
        </div>
        @endforeach
    </div>
    @else
    <div class="glass-card rounded-3xl p-12 text-center shadow-xl">
        <p class="text-slate-500 text-lg">Aucun objectif pour le moment</p>
        <a href="/goals/create" class="mt-4 inline-block rounded-full bg-green-600 px-6 py-3 text-white font-semibold hover:bg-green-700">Créer un objectif</a>
    </div>
    @endif
</div>
@endsection
