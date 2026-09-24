@extends('layouts.auth')

@section('title', 'Code de vérification - Athletica')

@section('content')
<div class="glass-card rounded-3xl p-8 shadow-xl">
    <div class="text-center text-5xl mb-4">📧</div>
    <h2 class="text-2xl font-bold text-center text-slate-900 mb-2">Vérification</h2>
    <p class="text-center text-slate-500 mb-6 text-sm">
        Un code à 6 chiffres a été envoyé à votre email
    </p>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl mb-6 text-sm">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form action="/login/verify" method="POST" class="space-y-5">
        @csrf
        <div>
            <input type="text" name="code" maxlength="6" required autofocus
                   class="w-full px-4 py-4 border-2 border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500 text-center text-3xl font-bold tracking-widest"
                   placeholder="000000"
                   pattern="[0-9]{6}"
                   inputmode="numeric">
        </div>
        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-2xl font-semibold hover:bg-green-700 transition">
            <i class="fas fa-check mr-2"></i>Valider le code
        </button>
    </form>

    <p class="text-center mt-6">
        <a href="/login" class="text-slate-400 text-sm hover:text-green-600">
            ← Retour à la connexion
        </a>
    </p>
</div>
@endsection
