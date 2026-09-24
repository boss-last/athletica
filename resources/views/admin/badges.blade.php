@extends('layouts.app')

@section('title', 'Gestion Badges - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">🏅 Gestion des Badges</h1>
        <a href="/admin/badges/create" class="bg-green-600 text-white px-5 py-2.5 rounded-2xl font-semibold hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>Nouveau badge
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($badges as $badge)
        <div class="glass-card rounded-3xl p-4 text-center shadow-lg relative">
            <form action="/admin/badges/{{ $badge->id }}" method="POST" class="absolute top-2 right-2">
                @csrf @method('DELETE')
                <button class="text-red-400 hover:text-red-600" onclick="return confirm('Supprimer ?')">
                    <i class="fas fa-times"></i>
                </button>
            </form>
            <span class="text-4xl">🏅</span>
            <h3 class="font-bold mt-2">{{ $badge->name }}</h3>
            <p class="text-xs text-slate-500">{{ $badge->description }}</p>
            <span class="text-sm text-yellow-600 font-bold">{{ $badge->points }} pts</span>
        </div>
        @endforeach
    </div>
</div>
@endsection
