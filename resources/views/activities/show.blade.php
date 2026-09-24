@extends('layouts.app')

@section('title', $activity->name ?? 'Activité - Athletica')

@push('scripts')
<script>
    @if($activity->gpsPoints->count() > 0)
    const map = L.map('map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
    const coords = @json($activity->gpsPoints->map(fn($p) => [$p->latitude, $p->longitude]));

    // Tracé du parcours
    L.polyline(coords, {color: '#22c55e', weight: 4, opacity: 0.9}).addTo(map);

    // Marqueurs départ (vert) / arrivée (rouge) via divIcon (aucun asset externe)
    const startIcon = L.divIcon({
        html: '<div style="background:#16a34a;width:18px;height:18px;border-radius:50%;border:3px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4);"></div>',
        className: '', iconSize: [18, 18], iconAnchor: [9, 9]
    });
    const endIcon = L.divIcon({
        html: '<div style="background:#dc2626;width:18px;height:18px;border-radius:50%;border:3px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4);"></div>',
        className: '', iconSize: [18, 18], iconAnchor: [9, 9]
    });

    L.marker(coords[0], { icon: startIcon }).addTo(map).bindPopup('🏁 Départ');
    L.marker(coords[coords.length - 1], { icon: endIcon }).addTo(map).bindPopup('🏆 Arrivée');

    // Zoom automatique sur l'ensemble du tracé
    map.fitBounds(L.polyline(coords).getBounds());
    @endif

    @if($hrData->count() > 0)
    new Chart(document.getElementById('hrChart'), {
        type: 'line',
        data: {
            labels: @json($hrData->pluck('timestamp')->map(fn($t) => $t->format('H:i'))),
            datasets: [{ label: 'FC (bpm)', data: @json($hrData->pluck('heart_rate')), borderColor: '#ef4444', fill: true, tension: 0.4 }]
        }
    });
    @endif

    @if($speedData->count() > 0)
    new Chart(document.getElementById('speedChart'), {
        type: 'line',
        data: {
            labels: @json($speedData->pluck('timestamp')->map(fn($t) => $t->format('H:i'))),
            datasets: [{ label: 'Vitesse (km/h)', data: @json($speedData->pluck('speed')), borderColor: '#3b82f6', fill: true, tension: 0.4 }]
        }
    });
    @endif

    @if($altitudeData->count() > 0)
    new Chart(document.getElementById('altitudeChart'), {
        type: 'line',
        data: {
            labels: @json($altitudeData->pluck('timestamp')->map(fn($t) => $t->format('H:i'))),
            datasets: [{ label: 'Altitude (m)', data: @json($altitudeData->pluck('altitude')), borderColor: '#8b5cf6', fill: true, tension: 0.4 }]
        }
    });
    @endif
</script>
@endpush

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-8 shadow-xl">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">{{ $activity->name ?? ucfirst($activity->sport_type) }}</h1>
                <p class="text-slate-500 mt-1">{{ $activity->start_time->format('d/m/Y H:i') }}</p>
                @if($activity->notes)<p class="text-slate-600 mt-2"><i class="fas fa-sticky-note mr-2"></i>{{ $activity->notes }}</p>@endif
            </div>
            <span class="rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">{{ $activity->sport_type }}</span>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card rounded-3xl p-5 text-center shadow-lg"><p class="text-slate-500 text-sm">Distance</p><p class="text-3xl font-bold text-green-600">{{ $activity->distance_km }} km</p></div>
        <div class="glass-card rounded-3xl p-5 text-center shadow-lg"><p class="text-slate-500 text-sm">Durée</p><p class="text-3xl font-bold text-blue-600">{{ gmdate('H:i:s', $activity->duration_seconds ?? 0) }}</p></div>
        <div class="glass-card rounded-3xl p-5 text-center shadow-lg"><p class="text-slate-500 text-sm">Allure</p><p class="text-3xl font-bold text-yellow-600">{{ $activity->pace ?? '-' }}</p></div>
        <div class="glass-card rounded-3xl p-5 text-center shadow-lg"><p class="text-slate-500 text-sm">Calories</p><p class="text-3xl font-bold text-red-600">{{ number_format($activity->calories_burned ?? 0) }}</p></div>
    </div>

    @if($activity->gpsPoints->count() > 0)
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">🗺️ Parcours</h2>
        <div id="map" class="rounded-2xl" style="height: 400px;"></div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if($hrData->count() > 0)<div class="glass-card rounded-3xl p-6 shadow-xl"><h2 class="text-lg font-semibold mb-4">❤️ Fréquence Cardiaque</h2><canvas id="hrChart" height="200"></canvas></div>@endif
        @if($speedData->count() > 0)<div class="glass-card rounded-3xl p-6 shadow-xl"><h2 class="text-lg font-semibold mb-4">⚡ Vitesse</h2><canvas id="speedChart" height="200"></canvas></div>@endif
        @if($altitudeData->count() > 0)<div class="glass-card rounded-3xl p-6 shadow-xl"><h2 class="text-lg font-semibold mb-4">⛰️ Altitude</h2><canvas id="altitudeChart" height="200"></canvas></div>@endif
    </div>

    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h2 class="text-lg font-semibold text-slate-900 mb-4">💬 Commentaires ({{ $activity->comments->count() }})</h2>
        <form action="/activities/{{ $activity->id }}/comment" method="POST" class="mb-4 flex gap-3">
            @csrf
            <input type="text" name="content" class="flex-1 px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500" placeholder="Ajouter un commentaire..." required>
            <button type="submit" class="bg-green-600 text-white px-5 py-3 rounded-2xl font-semibold hover:bg-green-700 transition">Envoyer</button>
        </form>
        @foreach($activity->comments as $comment)
        <div class="flex gap-3 py-3 border-b border-slate-100 last:border-b-0">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->full_name) }}&size=32&background=22c55e&color=fff" class="w-8 h-8 rounded-full">
            <div>
                <p class="font-semibold text-sm text-slate-800">{{ $comment->user->full_name }}</p>
                <p class="text-slate-600">{{ $comment->content }}</p>
                <small class="text-slate-400">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
