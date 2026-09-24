@extends('layouts.app')

@section('title', 'Plan - Athletica')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h1 class="text-2xl font-bold text-slate-800">{{ $plan->name }}</h1>
        <p class="text-slate-500 mt-2">{{ $plan->description }}</p>
        <div class="flex gap-3 mt-3">
            <span class="rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-700">{{ $plan->difficulty_level }}</span>
            <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">{{ $plan->sport_type }}</span>
            <span class="rounded-full bg-purple-100 px-3 py-1 text-sm font-semibold text-purple-700">{{ $plan->duration_weeks }} semaines</span>
        </div>
    </div>

    @foreach($plan->sessions->groupBy('week_number') as $week => $sessions)
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h2 class="text-lg font-bold mb-4">📅 Semaine {{ $week }}</h2>
        @foreach($sessions as $session)
        <a href="/training/session/{{ $session->id }}" class="flex items-center justify-between py-3 border-b border-slate-100 last:border-b-0 hover:bg-slate-50 px-2 rounded-xl transition">
            <div class="flex items-center gap-3">
                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold">J{{ $session->day_number }}</span>
                <div>
                    <p class="font-medium text-slate-800">{{ $session->title }}</p>
                    <p class="text-sm text-slate-500">{{ Str::limit($session->description, 60) }}</p>
                </div>
            </div>
            <div class="text-right text-sm">
                @if($session->planned_duration)<p class="font-semibold">⏱️ {{ $session->planned_duration }}min</p>@endif
                @if($session->planned_distance)<p class="font-semibold">📏 {{ number_format($session->planned_distance/1000, 1) }}km</p>@endif
                @if($session->intensity)<span class="rounded-full bg-red-100 px-2 py-0.5 text-xs text-red-700">{{ $session->intensity }}</span>@endif
            </div>
        </a>
        @endforeach
    </div>
    @endforeach
</div>
@endsection
