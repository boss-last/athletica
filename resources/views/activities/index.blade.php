@extends('layouts.app')

@section('title', 'Activités - Athletica')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-slate-900">📊 Mes Activités</h1>
            <a href="/activities/create" class="rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i>Nouvelle
            </a>
        </div>
    </div>

    <div class="glass-card rounded-3xl shadow-xl overflow-hidden">
        @foreach($activities as $activity)
        <a href="/activities/{{ $activity->id }}" class="flex items-center justify-between p-5 border-b border-slate-100 last:border-b-0 hover:bg-green-50 transition cursor-pointer">
            <div class="flex items-center gap-4">
                <span class="text-2xl">
                    @if($activity->sport_type == 'running') 🏃
                    @elseif($activity->sport_type == 'cycling') 🚴
                    @elseif($activity->sport_type == 'swimming') 🏊
                    @else 🏅
                    @endif
                </span>
                <div>
                    <h3 class="font-semibold text-slate-800">{{ $activity->name ?? $activity->sport_type }}</h3>
                    <p class="text-sm text-slate-500">{{ $activity->start_time->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-6 text-right">
                <div><p class="font-bold text-slate-800">{{ $activity->distance_km }} km</p><p class="text-xs text-slate-500">Distance</p></div>
                <div><p class="font-bold text-slate-800">{{ gmdate('H:i:s', $activity->duration_seconds ?? 0) }}</p><p class="text-xs text-slate-500">Durée</p></div>
                <div><p class="font-bold text-slate-800">{{ $activity->avg_heart_rate ?? '-' }} bpm</p><p class="text-xs text-slate-500">FC</p></div>
                <i class="fas fa-chevron-right text-slate-400"></i>
            </div>
        </a>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $activities->links() }}
    </div>
</div>
@endsection
