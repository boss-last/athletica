@extends('layouts.app')

@section('title', 'Nouvelle Activité - Athletica')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div class="glass-card rounded-3xl p-8 shadow-xl">
        <h1 class="text-3xl font-bold text-slate-900 mb-6">➕ Nouvelle Activité</h1>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6">
            @foreach($errors->all() as $error)
                <p><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="/activities" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Type de sport *</label>
                <select name="sport_type" required class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500">
                    <option value="">Choisir...</option>
                    <option value="running">🏃 Course à pied</option>
                    <option value="cycling">🚴 Cyclisme</option>
                    <option value="swimming">🏊 Natation</option>
                    <option value="walking">🚶 Marche</option>
                    <option value="hiking">⛰️ Randonnée</option>
                    <option value="fitness">💪 Fitness</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Nom</label>
                <input type="text" name="name" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="Ex: Sortie longue">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date *</label>
                    <input type="datetime-local" name="start_time" value="{{ now()->format('Y-m-d\TH:i') }}" required class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Durée (secondes)</label>
                    <input type="number" name="duration_seconds" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="Ex: 3600">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Distance (m)</label>
                    <input type="number" name="distance_meters" step="0.01" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="Ex: 10000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Calories</label>
                    <input type="number" name="calories_burned" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="Ex: 500">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">FC Moy.</label>
                    <input type="number" name="avg_heart_rate" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="bpm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">FC Max</label>
                    <input type="number" name="max_heart_rate" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="bpm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Dénivelé (m)</label>
                    <input type="number" name="elevation_gain" step="0.1" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="Ex: 150">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="Comment s'est passée la séance ?"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-2xl font-semibold hover:bg-green-700 transition">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
                <a href="/activities" class="px-6 py-3 border border-slate-200 rounded-2xl text-slate-600 hover:bg-slate-50 transition">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
