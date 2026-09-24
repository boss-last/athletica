@extends('layouts.app')

@section('title', 'Classement - Athletica')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h1 class="text-2xl font-bold">🏆 {{ $challenge->title }}</h1>
        <p class="text-slate-500 mt-1">{{ $challenge->description }}</p>
        <div class="flex items-center gap-4 mt-3 text-sm text-slate-400">
            <span>🎯 {{ number_format($challenge->target_value) }}</span>
            <span>📅 {{ $challenge->start_date->format('d/m/Y') }} - {{ $challenge->end_date->format('d/m/Y') }}</span>
            <span>👥 {{ $challenge->participants->count() }}</span>
        </div>
    </div>

    <h2 class="text-xl font-bold text-slate-800">📊 Classement</h2>
    @if($challenge->participants->count() > 0)
    <div class="glass-card rounded-3xl shadow-xl overflow-hidden">
        @foreach($challenge->participants as $index => $participant)
        <div class="flex items-center p-4 {{ $index % 2 == 0 ? 'bg-slate-50/50' : '' }} {{ $participant->id == auth()->id() ? 'border-l-4 border-green-500' : '' }}">
            <div class="w-8 text-center font-bold text-lg {{ $index == 0 ? 'text-yellow-500' : ($index == 1 ? 'text-slate-400' : ($index == 2 ? 'text-orange-500' : 'text-slate-300')) }}">
                @if($index == 0) 🥇 @elseif($index == 1) 🥈 @elseif($index == 2) 🥉 @else {{ $index + 1 }} @endif
            </div>
            <img src="https://ui-avatars.com/api/?name={{ urlencode($participant->full_name) }}&size=40&background=22c55e&color=fff" class="w-10 h-10 rounded-full ml-3">
            <div class="ml-3 flex-1">
                <p class="font-medium">{{ $participant->full_name }}</p>
                <div class="h-2 rounded-full bg-slate-100 mt-1"><div class="h-2 rounded-full bg-green-500" style="width: {{ $challenge->target_value > 0 ? ($participant->pivot->current_progress/$challenge->target_value)*100 : 0 }}%"></div></div>
            </div>
            <div class="ml-4 text-right"><p class="font-bold">{{ number_format($participant->pivot->current_progress) }}</p><p class="text-xs text-slate-400">/{{ number_format($challenge->target_value) }}</p></div>
        </div>
        @endforeach
    </div>
    @else
    <p class="text-center text-slate-500 py-8">Aucun participant</p>
    @endif
</div>
@endsection
