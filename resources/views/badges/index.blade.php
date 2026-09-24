@extends('layouts.app')

@section('title', 'Badges - Athletica')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h1 class="text-3xl font-bold text-slate-900">🏅 Badges</h1>
    </div>

    @php $allBadges = App\Models\Badge::all(); $userBadges = auth()->user()->badges->pluck('id')->toArray(); @endphp

    @if($allBadges->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($allBadges as $badge)
        <div class="glass-card rounded-3xl p-4 text-center shadow-xl {{ in_array($badge->id, $userBadges) ? 'border-2 border-green-500' : 'opacity-50' }}">
            <span class="text-4xl">{{ in_array($badge->id, $userBadges) ? '🏅' : '🔒' }}</span>
            <h3 class="font-bold mt-2 text-slate-800">{{ $badge->name }}</h3>
            <p class="text-xs text-slate-500">{{ $badge->description }}</p>
            <span class="text-sm font-bold text-yellow-600">{{ $badge->points }} pts</span>
        </div>
        @endforeach
    </div>
    @else
    <div class="glass-card rounded-3xl p-12 text-center shadow-xl">
        <p class="text-slate-500">Aucun badge disponible</p>
    </div>
    @endif
</div>
@endsection
