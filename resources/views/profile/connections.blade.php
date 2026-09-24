@extends('layouts.app')

@section('title', 'Connexions - Athletica')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h1 class="text-3xl font-bold text-slate-900">🔗 Plateformes Connectées</h1>
    </div>

    <div class="space-y-4">
        @foreach(['strava', 'garmin', 'fitbit', 'apple_health', 'google_fit', 'polar'] as $platform)
            @php $conn = $connections->where('platform_name', $platform)->first(); @endphp
            <div class="glass-card rounded-3xl p-5 shadow-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">@if($platform=='strava')🟠@elseif($platform=='garmin')🔵@elseif($platform=='fitbit')🟢@else📱@endif</span>
                    <div>
                        <p class="font-bold text-slate-800">{{ ucfirst(str_replace('_', ' ', $platform)) }}</p>
                        @if($conn)
                            <p class="text-sm text-green-600">✅ Connecté</p>
                            <p class="text-xs text-slate-400">Dernière synchro : {{ $conn->last_synced_at?->diffForHumans() ?? 'Jamais' }}</p>
                        @else
                            <p class="text-sm text-slate-400">Non connecté</p>
                        @endif
                    </div>
                </div>
                @if($platform == 'strava' && !$conn)
                    <a href="/connect/strava" class="rounded-full bg-orange-500 px-5 py-2 text-sm font-semibold text-white hover:bg-orange-600 transition">Connecter</a>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endsection
