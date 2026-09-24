@extends('layouts.app')

@section('title', 'Plans d\'Entraînement - Athletica')

@section('content')
<div class="space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h1 class="text-3xl font-bold text-slate-900">📋 Plans d'Entraînement</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $plan)
        <a href="/training/{{ $plan->id }}" class="glass-card rounded-3xl p-6 shadow-xl hover:shadow-2xl transition block">
            <h2 class="text-xl font-bold text-slate-800">{{ $plan->name }}</h2>
            <p class="text-sm text-slate-500 mt-1">{{ Str::limit($plan->description, 80) }}</p>
            <div class="flex items-center gap-3 mt-4">
                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ $plan->sport_type }}</span>
                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700">{{ $plan->difficulty_level }}</span>
            </div>
            <div class="flex justify-between mt-4 text-sm text-slate-400">
                <span><i class="far fa-calendar mr-1"></i>{{ $plan->duration_weeks }} semaines</span>
                <span><i class="fas fa-list mr-1"></i>{{ $plan->sessions->count() }} séances</span>
            </div>
            @if($plan->coach)
            <p class="text-sm text-slate-400 mt-2"><i class="fas fa-user mr-1"></i>{{ $plan->coach->full_name }}</p>
            @endif
        </a>
        @endforeach
    </div>
</div>
@endsection
