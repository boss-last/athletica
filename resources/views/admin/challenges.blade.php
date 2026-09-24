@extends('layouts.app')

@section('title', 'Gestion Challenges - Admin')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold">🏆 Gestion des Challenges</h1>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl">{{ session('success') }}</div>
    @endif

    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h2 class="font-bold text-lg mb-4">➕ Créer un challenge officiel</h2>
        <form action="/admin/challenges" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="title" placeholder="Titre" required class="w-full px-4 py-3 border rounded-2xl">
            <textarea name="description" placeholder="Description" rows="2" class="w-full px-4 py-3 border rounded-2xl"></textarea>
            <div class="grid grid-cols-3 gap-4">
                <select name="challenge_type" required class="px-4 py-3 border rounded-2xl">
                    <option value="distance">📏 Distance</option>
                    <option value="duration">⏱️ Durée</option>
                    <option value="frequency">🔄 Fréquence</option>
                </select>
                <input type="number" name="target_value" placeholder="Objectif" required class="px-4 py-3 border rounded-2xl">
                <input type="date" name="start_date" required class="px-4 py-3 border rounded-2xl">
            </div>
            <input type="date" name="end_date" required class="w-full px-4 py-3 border rounded-2xl">
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-2xl font-bold hover:bg-green-700">
                Créer le challenge
            </button>
        </form>
    </div>

    <h2 class="font-bold text-lg">📋 Challenges existants</h2>
    <div class="glass-card rounded-3xl shadow-xl overflow-hidden">
        @foreach($challenges as $c)
        <div class="flex items-center justify-between p-4 border-b last:border-b-0">
            <div>
                <p class="font-medium">{{ $c->title }}</p>
                <p class="text-xs text-slate-400">par {{ $c->creator->full_name ?? 'N/A' }} · {{ $c->challenge_type }} - {{ $c->target_value }}</p>
            </div>
            <form action="/admin/challenges/{{ $c->id }}" method="POST">
                @csrf @method('DELETE')
                <button class="text-red-500 hover:text-red-700" onclick="return confirm('Supprimer ?')">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection
