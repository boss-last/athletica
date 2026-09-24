@extends('layouts.app')

@section('title', 'Nouvel Objectif - Athletica')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div class="glass-card rounded-3xl p-8 shadow-xl">
        <h1 class="text-3xl font-bold text-slate-900 mb-6">🎯 Nouvel Objectif</h1>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6">
            @foreach($errors->all() as $error)<p><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>@endforeach
        </div>
        @endif

        <form action="/goals" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Titre *</label>
                <input type="text" name="title" required class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="Ex: Courir 100km ce mois">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="2" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="Détails..."></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type *</label>
                    <select name="goal_type" required class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500">
                        <option value="distance">📏 Distance</option>
                        <option value="duration">⏱️ Durée</option>
                        <option value="frequency">🔄 Fréquence</option>
                        <option value="calories">🔥 Calories</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Valeur cible *</label>
                    <input type="number" name="target_value" required step="0.01" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="Ex: 100">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date de début</label>
                    <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date de fin</label>
                    <input type="date" name="end_date" required class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500">
                </div>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-2xl font-semibold hover:bg-green-700 transition">
                <i class="fas fa-save mr-2"></i>Créer l'objectif
            </button>
        </form>
    </div>
</div>
@endsection
