@extends('layouts.app')

@section('title', 'Paiement réussi - Athletica')

@section('content')
<div class="glass-card rounded-3xl p-8 text-center max-w-md mx-auto">
    <div class="text-6xl mb-4">🎉</div>
    <h1 class="text-2xl font-bold text-green-600">Paiement réussi !</h1>
    <p class="text-slate-500 mt-2">Bienvenue dans Athletica Premium !</p>
    <a href="/dashboard" class="mt-6 inline-block bg-green-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-green-700 transition">
        Accéder au Dashboard
    </a>
</div>
@endsection
