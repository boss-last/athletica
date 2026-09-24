@extends('layouts.app')

@section('title', 'Réseau - Athletica')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    <div class="glass-card rounded-3xl p-6 shadow-xl">
        <h1 class="text-3xl font-bold text-slate-900">👥 Réseau</h1>
    </div>

    <div class="flex gap-4">
        <a href="?tab=followers" class="rounded-full px-5 py-2.5 text-sm font-semibold transition {{ request('tab', 'followers') == 'followers' ? 'bg-green-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">Abonnés ({{ $user->followers->count() }})</a>
        <a href="?tab=following" class="rounded-full px-5 py-2.5 text-sm font-semibold transition {{ request('tab') == 'following' ? 'bg-green-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">Abonnements ({{ $user->following->count() }})</a>
    </div>

    @php $list = request('tab', 'followers') == 'followers' ? $user->followers : $user->following; @endphp
    <div class="glass-card rounded-3xl shadow-xl divide-y divide-slate-100">
        @forelse($list as $person)
        <div class="flex items-center justify-between p-5">
            <div class="flex items-center gap-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($person->full_name) }}&size=40&background=22c55e&color=fff" class="w-10 h-10 rounded-full">
                <div><p class="font-medium text-slate-800">{{ $person->full_name }}</p><p class="text-sm text-slate-400">@{{ $person->username }}</p></div>
            </div>
            @if(auth()->id() != $person->id)
            <form action="/follow/{{ $person->id }}" method="POST">
                @csrf
                <button class="rounded-full border border-slate-200 px-4 py-2 text-sm hover:bg-slate-50 transition">
                    {{ auth()->user()->following->contains($person->id) ? 'Ne plus suivre' : 'Suivre' }}
                </button>
            </form>
            @endif
        </div>
        @empty
        <p class="text-center text-slate-500 py-8">Aucun abonné</p>
        @endforelse
    </div>
</div>
@endsection
