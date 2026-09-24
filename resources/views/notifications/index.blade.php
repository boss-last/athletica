@extends('layouts.app')

@section('title', 'Notifications - Athletica')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl flex items-center justify-between">
        <h1 class="text-3xl font-bold text-slate-900">🔔 Notifications</h1>
        @if(auth()->user()->unreadNotifications()->count() > 0)
        <form action="/notifications/read-all" method="POST">
            @csrf @method('PATCH')
            <button class="text-green-600 font-semibold text-sm hover:underline">Tout marquer comme lu</button>
        </form>
        @endif
    </div>

    @if($notifications->count() > 0)
    <div class="glass-card rounded-3xl shadow-xl divide-y divide-slate-100">
        @foreach($notifications as $notification)
        <div class="p-5 {{ $notification->read_at ? '' : 'bg-blue-50/50' }}">
            <p class="text-sm text-slate-700">{{ $notification->data['message'] ?? 'Notification' }}</p>
            <div class="flex items-center gap-3 mt-1">
                <small class="text-slate-400">{{ $notification->created_at->diffForHumans() }}</small>
                @if(!$notification->read_at)
                <form action="/notifications/{{ $notification->id }}/read" method="POST">
                    @csrf @method('PATCH')
                    <button class="text-xs font-semibold text-blue-600 hover:underline">Marquer lue</button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="glass-card rounded-3xl p-12 text-center shadow-xl">
        <p class="text-slate-500">Aucune notification</p>
    </div>
    @endif
</div>
@endsection
