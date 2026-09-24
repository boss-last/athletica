@extends('layouts.app')

@section('title', 'Fitness Metrics - Athletica')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl flex items-center justify-between">
        <h1 class="text-3xl font-bold text-slate-900">📊 Fitness Metrics</h1>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>Ajouter
        </button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card rounded-3xl p-5 text-center shadow-lg"><p class="text-slate-400 text-sm">VO2max</p><p class="text-2xl font-bold text-blue-600">{{ $latest->vo2max ?? '-' }}</p></div>
        <div class="glass-card rounded-3xl p-5 text-center shadow-lg"><p class="text-slate-400 text-sm">FC Repos</p><p class="text-2xl font-bold text-green-600">{{ $latest->resting_heart_rate ?? '-' }} <span class="text-sm">bpm</span></p></div>
        <div class="glass-card rounded-3xl p-5 text-center shadow-lg"><p class="text-slate-400 text-sm">HRV</p><p class="text-2xl font-bold text-purple-600">{{ $latest->heart_rate_variability ?? '-' }} <span class="text-sm">ms</span></p></div>
        <div class="glass-card rounded-3xl p-5 text-center shadow-lg"><p class="text-slate-400 text-sm">Forme</p><p class="text-2xl font-bold text-yellow-600">{{ $latest->form_score ?? '-' }}</p></div>
    </div>

    @if($metrics->count() > 2)
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h2 class="text-lg font-bold mb-4">📈 Évolution</h2>
        <canvas id="fitnessChart" height="200"></canvas>
    </div>
    @endif

    <div class="glass-card rounded-3xl p-6 shadow-xl overflow-x-auto">
        <h2 class="text-lg font-bold mb-4">📋 Historique</h2>
        <table class="w-full text-sm">
            <thead><tr class="border-b border-slate-100 text-left text-slate-500"><th class="p-2">Date</th><th class="p-2 text-right">VO2max</th><th class="p-2 text-right">FC Repos</th><th class="p-2 text-right">HRV</th><th class="p-2 text-right">Forme</th><th class="p-2 text-right">Fitness</th><th class="p-2 text-right">Fatigue</th></tr></thead>
            <tbody>
                @foreach($metrics as $m)
                <tr class="border-b border-slate-50 hover:bg-slate-50"><td class="p-2">{{ $m->metric_date->format('d/m/Y') }}</td><td class="p-2 text-right">{{ $m->vo2max ?? '-' }}</td><td class="p-2 text-right">{{ $m->resting_heart_rate ?? '-' }}</td><td class="p-2 text-right">{{ $m->heart_rate_variability ?? '-' }}</td><td class="p-2 text-right font-bold">{{ $m->form_score ?? '-' }}</td><td class="p-2 text-right">{{ $m->fitness_score ?? '-' }}</td><td class="p-2 text-right">{{ $m->fatigue_score ?? '-' }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Ajout -->
<div id="addModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="glass-card rounded-3xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4"><h2 class="text-xl font-bold">Ajouter une métrique</h2><button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 text-xl">&times;</button></div>
        <form action="/fitness" method="POST" class="space-y-3">
            @csrf
            <input type="date" name="metric_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border rounded-xl">
            <div class="grid grid-cols-2 gap-3">
                <input type="number" name="vo2max" step="0.1" class="px-3 py-2 border rounded-xl" placeholder="VO2max">
                <input type="number" name="resting_heart_rate" class="px-3 py-2 border rounded-xl" placeholder="FC Repos">
                <input type="number" name="heart_rate_variability" step="0.1" class="px-3 py-2 border rounded-xl" placeholder="HRV">
                <input type="number" name="fitness_score" class="px-3 py-2 border rounded-xl" placeholder="Fitness">
                <input type="number" name="fatigue_score" class="px-3 py-2 border rounded-xl" placeholder="Fatigue">
                <input type="number" name="form_score" class="px-3 py-2 border rounded-xl" placeholder="Forme">
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-xl font-semibold hover:bg-green-700 transition">Enregistrer</button>
        </form>
    </div>
</div>

@if($metrics->count() > 2)
@push('scripts')
<script>
    const ctx = document.getElementById('fitnessChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($metrics->pluck('metric_date')->map(fn($d) => $d->format('d/m'))->reverse()),
            datasets: [
                { label: 'Fitness', data: @json($metrics->pluck('fitness_score')->reverse()), borderColor: '#22c55e', fill: false },
                { label: 'Fatigue', data: @json($metrics->pluck('fatigue_score')->reverse()), borderColor: '#ef4444', fill: false },
                { label: 'Forme', data: @json($metrics->pluck('form_score')->reverse()), borderColor: '#3b82f6', fill: false }
            ]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endpush
@endif
@endsection
